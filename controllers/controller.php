<?php
class Controller
{
  private $_f3;

  /**
   * Controller constructor.
   * @param $f3
   */
  public function __construct($f3)
  {
    $this -> _f3 = $f3;
  }


  function home()
  {
    global $data;

    for($i = 1; $i < 10; $i++) {
      $data->updateLastPosted($i);
    }

    $view = new Template();
    echo $view->render('views/home.html');
    /*if($_SESSION['loggedin'] == true) {
      echo $view->render('views/feed.html');
    } else {
      echo $view->render('views/home.html');
    }*/


  }

  function login()
  {
    global $login;
    if($_SESSION['loggedin'] == true) {
      $this->_f3->reroute('/');
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $login->logInUser();
    }
    if(!empty($this->_f3->get('success')) && empty($this->_f3->get('errors'))){
      $this->_f3->reroute('/');
    }

    // render login.html
    $view = new Template();
    echo $view->render('views/login.html');

  }

  function register()
  {
    global $register;
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $register->registerUser();
      if(empty($this->_f3->get('errors'))){
        $this->_f3->reroute('/');
      }
    }

    /* Debug */
    //  echo "<pre>";
    //  echo "POST ARRAY:";
    //  echo print_r($_POST,true);
    //  echo "<br> SESSION ARRAY:";
    //  echo print_r($_SESSION,true);
    //  echo "</pre>";

    // render register.html
    $view = new Template();
    echo $view->render('views/register.html');
  }

  function community($community_id)
  {
    /*
   * TODO: if community_id is outside certain bounds redirect to error page.
   */

    global $community;
    global $data;
    $community->viewPosts($community_id);

    $name = $data->getCommunityName($community_id);

    $this->_f3->set("pageTitle", $name);

    /* Debug */
    // echo $community_id;
    // echo var_dump($_SESSION);
    // echo "<pre>";
    // echo print_r($f3->get("posts"));
    // echo "</pre>";



    $view = new Template();
    echo $view->render('views/community.html');

  }
  function posts($community_id, $post_id)
  {
    /*
   * TODO: if community_id is outside certain bounds redirect to error page.
   * TODO: if post_id is outside certain bounds redirect to error page.
   */

    global $community;
    global $data;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $text = trim($_POST['commentSubmit']);
      $community->submitComment($community_id, $post_id, $text);
    }

    $name = $data->getCommunityName($community_id);

    $this->_f3->set("pageTitle", $name);
    $community->viewPost($post_id);
    $community->viewComments($community_id, $post_id);


    $view = new Template();
    echo $view->render('views/comments.html');
  }

  // TODO: Sanitize
  function submit($community_id)
  {
    echo "submit function reached";
    global $community;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      echo "request method reached";
      $subject = $_POST['postSubject'];
      if(!empty($_POST['postText'])) {
        $text = $_POST['postText'];
      } else {
        $text = "";
      }
      if($_POST['postMedia']) {
        $media = $_POST['postMedia'];
      } else {
        $media = "";
      }
      /* Debug */
      // echo $media;

      if(!$_SESSION['loggedin']) {
        $this->_f3->reroute("login");
      }

      $community->submitPost($community_id, $subject, $text, $media);
    }

      $view = new Template();
      echo $view->render('views/submit.html');
  }


}