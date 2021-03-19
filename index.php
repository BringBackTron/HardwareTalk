<?php
/*
 * Authors: George McMullen, Shawn Potter
 * TODO: create logout
 * TODO: hide login button if user is logged in
 * TODO: work on feed page
 * TODO: design comments
 * TODO: create comments view for posts (partially done)
 * TODO: create comment submissions
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
$controller = new Controller($f3);
$register = new Register($dbh, $f3);
$login = new Login($dbh, $f3);
$validator = new Validator();
$community = new Community($dbh, $f3);
$data = new DataLayer($dbh, $f3);


// set fat-free debugging
$f3->set('DEBUG', 3);

// define a default route (home page)
$f3->route('GET /', function($f3){

  global $controller;

  $controller->home();

});

// define route to login page
$f3->route('GET|POST /login', function(){

  global $controller;

  $controller->login();
});

// define route to register page
$f3->route('GET|POST /register', function(){

  global $controller;

  $controller->register();

});

$f3->route('GET /gaming', function(){ //deprecated, remove later


  /* Debug */
  // echo var_dump($_SESSION);

  $view = new Template();
  echo $view->render('views/communities/gaming.html');
});

$f3->route('GET /community/12', function($f3){ //deprecated, remove later

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

// define a dynamic route to each community page
$f3->route('GET /community/@community_id', function($f3){

  global $controller;

  $community_id = $f3->get("PARAMS.community_id");

  $controller->community($community_id);

});

// define a dynamic route to each page for posts
$f3->route('GET /community/@community_id/@post_id', function($f3){

  global $controller;

  $community_id = $f3->get("PARAMS.community_id");
  $post_id = $f3->get("PARAMS.post_id");

  $controller->posts($community_id, $post_id);

});

//run fat free
$f3->run();