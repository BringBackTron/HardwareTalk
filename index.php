<?php
/*
 * Authors: George McMullen, Shawn Potter
 * TODO: work on feed page
 * TODO: work on homepage cards
 * TODO: switch login to using username
 * TODO: store user session with the user class (admin are stored with the admin class)
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
$logout = new Logout($f3);
$user = new User($dbh);


//runs on all pages

if($_SESSION['loggedin']==true)
  $user->updateThumbs($_SESSION['user_id']);

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

// define a dynamic route to each community page
$f3->route('GET /community/@communityID', function($f3){

    global $controller;

    $communityID = $f3->get("PARAMS.communityID");

    if($communityID <= 0 || $communityID >= 10){
      $f3->reroute("/");
    }

    $controller->community($communityID);

});

// define a dynamic route to each page for posts
$f3->route('GET|POST /community/@communityID/@postID', function($f3){

    global $controller;


    $communityID = $f3->get("PARAMS.communityID");
    $postID = $f3->get("PARAMS.postID");

    if($communityID <= 0 || $communityID >= 10){
      $f3->reroute("/");
    }



  $controller->posts($communityID, $postID);

});

$f3->route('GET|POST /community/@communityID/submit', function($f3){

    global $controller;

    $communityID = $f3->get("PARAMS.communityID");

    $controller->submit($communityID);

});

$f3->route('GET /logout', function($f3){
    global $logout;
    $logout->logout($f3);
});

$f3->route('GET /testPost', function($f3){

    $view = new Template();
    echo $view->render('views/testPost.html');

});

//run fat free
$f3->run();