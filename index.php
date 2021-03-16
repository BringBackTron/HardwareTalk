<?php
/*
 * TODO: create logout
 * TODO: hide login button if user is logged in
 * TODO: work on feed page
 * TODO: research last_post tracking
 * TODO: design comments
 * TODO: create comments view for posts
 * TODO: create post and comment submissions
 */

//The Controller

//turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//start a session
session_start();

//require autoload file
require $_SERVER['DOCUMENT_ROOT'].'/../ht-db-config.php';
require_once("vendor/autoload.php");


//create an instance of the base class
$f3 = Base::instance();

//instanitate classes
$register = new Register($dbh, $f3);
$login = new Login($dbh, $f3);
$validator = new Validator();
$community = new Community($dbh, $f3);


//set fat-free debugging
$f3->set('DEBUG', 3);

//Define a default route (home page)
$f3->route('GET /', function($f3){
  $view = new Template();
  if($_SESSION['loggedin'] == true) {
    echo $view->render('views/feed.html');
  } else {
    echo $view->render('views/home.html');
  }

});

$f3->route('GET|POST /login', function($f3){
  global $login;
  if($_SESSION['loggedin'] == true) {
    $f3->reroute('/');
  }
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login->logInUser();
  }
  if(!empty($f3->get('success')) && empty($f3->get('errors'))){
    $f3->reroute('/');
  }

  // render login.html
  $view = new Template();
  echo $view->render('views/login.html');
});

$f3->route('GET|POST /register', function($f3, $dbh){
  global $register;
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $register->registerUser();
    if(empty($f3->get('errors'))){
      $f3->reroute('/');
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

});

$f3->route('GET /gaming', function(){
  
  /* Debug */
  // echo var_dump($_SESSION);
  
  // render home.html
  $view = new Template();
  echo $view->render('views/communities/gaming.html');
});

$f3->route('GET /community/12', function($f3){

  global $community;
  $community->viewPosts();
  
  /* Debug */
  // echo var_dump($_SESSION);
  // render home.html
  // echo "<pre>";
  // echo print_r($f3->get("posts"));
  // echo "</pre>";

  $view = new Template();
  echo $view->render('views/communities/diy.html');

});

$f3->route('GET /community/@community_id', function($f3){

  $community_id = $f3->get("PARAMS.community_id");
  
  /*
   * TODO: if community_id is outside certain bounds redirect to error page.
   */
  
  global $community;
  $community->viewPosts($community_id);
  
  /* Debug */
  // echo $community_id;
  // echo var_dump($_SESSION);
  // echo "<pre>";
  // echo print_r($f3->get("posts"));
  // echo "</pre>";
  
  
  
  $view = new Template();
  echo $view->render('views/community.html');
  
});




//run fat free
$f3->run();