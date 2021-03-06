<?php
  /*
   *
   */
  
  //The Controller
  
  //turn on error reporting
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  
  //start a session
  session_start();
  
  //require autoload file
  require_once("vendor/autoload.php");
  
  //create an instance of the base class
  $f3 = Base::instance();
  
  //set fat-free debugging
  $f3->set('DEBUG', 3);
  
  //Define a default route (home page)
  $f3->route('GET /', function(){
    // render home.html
    $view = new Template();
    echo $view->render('views/home.html');
  });

    $f3->route('GET /login', function(){
    // render home.html
    $view = new Template();
    echo $view->render('views/login.html');
    });

$f3->route('GET /register', function(){
    // render home.html
    $view = new Template();
    echo $view->render('views/register.html');
});


//run fat free
  $f3->run();