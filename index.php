<?php
/*
 * Authors: George McMullen, Shawn Potter
 */

//turn on error reporting
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

//require autoload file
require_once("vendor/autoload.php");
require $_SERVER['DOCUMENT_ROOT'].'/../ht-db-config.php';

//start a session
session_start();


//create an instance of the base class
$f3 = Base::instance();

//instanitate classes
$controller = new Controller($f3, $dbh);
$register = new Register($dbh, $f3);
$login = new Login($dbh, $f3);
$validator = new Validator();
$community = new Community($dbh, $f3);
$data = new DataLayer($dbh, $f3);
$logout = new Logout($f3);

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

// Submit page with function
$f3->route('GET|POST /community/@communityID/submit', function($f3){

    global $controller;

    $communityID = $f3->get("PARAMS.communityID");

    $controller->submit($communityID);

});

// Logout page with function
$f3->route('GET /logout', function($f3){
    global $logout;
    $logout->logout($f3);
});

//run fat free
$f3->run();