<?php

/**
 * Class Controller
 *
 * Class Controller contains functions that handle the rendering of the views
 * inside the fat-free framework. Some functions also handle post data.
 *
 * @author George McMullen
 * @author Shawn Potter
 * @version 1.0
 */
class Controller
{
  private $_f3;
  private $_dbh;

  /**
   * Controller constructor.
   * @param $f3 object fat-free object
   */
  public function __construct($f3, $dbh)
  {
    $this -> _f3 = $f3;
    $this -> _dbh = $dbh;
  }


  /**
   * Home function to display home.html on the "/" route
   *
   * Home function to display home.html on the "/" route. Populates
   * the homepage with information from the database to provide community
   * names and the latest posts.
   */
  public function home()
  {
    //call global(s)
    global $data;

    for($i = 1; $i < 10; $i++) {
      $data->updateLastPosted($i);
    }

    $communities = $data->populateHomePage();
    $this->_f3->set("communities", $communities);

    $view = new Template();
    echo $view->render('views/home.html');
  }

  /**
   * Login function to display login.html on the "login" route
   *
   * Login function to display login.html on the "login" route. If the user is
   * already logged in they are redirected back to the homepage, if the user
   * succesfully logs in they are also redirected back to the homepage.
   */
  public function login()
  {
    //call global(s)
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

  /**
   * Register function to display register.html on the "register" route
   *
   * Register function to display register.html on the "register" route.
   * Gets information from the post data to register the user on the database.
   */
  public function register()
  {
    //call global(s)
    global $register;
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $register->registerUser();
      if(empty($this->_f3->get('errors'))){
        $this->_f3->reroute('/');
      }
    }

    // render register.html
    $view = new Template();
    echo $view->render('views/register.html');
  }

  /**
   * Community function to display community.html on the "commmunity" route
   *
   * Community function to display community.html on the "commmunity" route.
   * Gets all posts associated with the community and displays them.
   *
   * @param $communityID integer id of the community
   */
  public function community($communityID)
  {
    //call global(s)
    global $community;
    global $data;


    $community->viewPosts($communityID);

    $name = $data->getCommunityName($communityID);

    $this->_f3->set("pageTitle", $name);

    $view = new Template();
    echo $view->render('views/community.html');

  }

  /**
   * Posts function to display comments.html on the "post" route
   *
   * Posts function to display comments.html on the "post" route. Passes
   * in the id of the community and post using params and populates the
   * page with the comments belonging to the post. Also contains a
   * statement to run an admin function.
   *
   * @param $communityID integer id of the community
   * @param $postID integer id of the post
   */
  public function posts($communityID, $postID)
  {
    //call global(s)
    global $community;
    global $data;


    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'Submit Comment') {
      $text = trim($_POST['commentSubmit']);
      if(empty($text)) {
        $this->_f3->set('errors["emptyComment"]', "Comment can not be empty");
      } else {
        $community -> submitComment($communityID, $postID, $text);
      }
    }

    //admin control
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])){
      $_SESSION['user']->deleteComment(intval($_POST['delete']), $this->_dbh, $postID);
    }

    $name = $data->getCommunityName($communityID);

    $this->_f3->set("pageTitle", $name);
    $community->viewPost($postID);
    $community->viewComments($communityID, $postID);


    $view = new Template();
    echo $view->render('views/comments.html');
  }

  /**
   * Submit function to display submit.html on the "submit" route
   *
   * Submit function to display submit.html on the "submit" route. Retreives
   * the post data and passes it into the submitPost function. If the user
   * is not logged in, it redirects them to the login page.
   *
   * @param $communityID integer id of the community
   */
  public function submit($communityID)
  {
    //call global(s)
    global $community;

    //redirect users that aren't logged in
    if(!$_SESSION['loggedin']) {
      $this->_f3->reroute("login");
    }

    //if the page posts to itself
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $subject = $_POST['postSubject'];
      if(!empty($_POST['postText'])) {
        $text = trim($_POST['postText']);
      } else {
        $text = "";
      }
      if($_POST['postMedia']) {
        $media = trim($_POST['postMedia']);
      } else {
        $media = "";
      }

      //submit the post
      $community->submitPost($communityID, $subject, $text, $media);
    }

    //render view
    $view = new Template();
    echo $view->render('views/submit.html');
  }
}