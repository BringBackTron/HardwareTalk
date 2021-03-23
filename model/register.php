<?php

class Register{
  private $_dbh;
  private $_f3;
  function __construct($dbh, $f3)
  {
    $this->_dbh = $dbh;
    $this->_f3 = $f3;
  }
  function registerUser()
  {
    //assign globals
    global $validator;
    
    //Define the query
    $sql = "INSERT INTO users (username, user_email, user_ip, user_password)
          VALUES (:username, :userEmail, :userIp, :userPassword);";
  
    //Prepare the statement
    $statement = $this->_dbh->prepare($sql);
    
    //create variables to store the post data
    $username = "";
    $email = "";
    $password = "";
    $ipaddress = $this->get_ip();
  
    
    /* validate username */
    //check to see if username is empty or not
    if(empty($_POST['username'])){
      $this->_f3->set('errors["registerName"]', "Username can not be empty");
    }
    //validate the email (nothing done at the moment)
    else if(trim($_POST['username'])) {
      $username = $_POST['username'];
    }
    //else set errors
    else {
      $this -> _f3 -> set('errors["registerName"]', "Username is not valid");
      //echo var_dump($this->_f3->get('errors'));
    }
  
    /* validate email */
    //if email is empty then set error
    if(empty($_POST['email'])){
      $this->_f3->set('errors["registerEmail"]', "Email can not be empty");
    }
    //if email is valid then set email
    else if($validator->validEmail($_POST['email'])) {
      $email = $_POST['email'];
    }
    //else set error that email is not valid
    else {
      $this->_f3->set('errors["registerEmail"]', "Email is not valid");
      //echo var_dump($this->_f3->get('errors'));
    }
  
    /*
     *  Retreive password from post data and then hash with BCRYPT if valid
     *  Passwords don't need to be checked for sql injection because they
     *  get hashed and salted before they're ever submitted to the database
     */
    //if password is empty then set error
    if(empty($_POST['password'])){
      $this->_f3->set('errors["registerPass"]', "Password can not be empty");
    }
    //if confirm password is empty then set error
    else if(empty($_POST['confirmPassword'])){
      $this->_f3->set('errors["registerConfirmPass"]', "Confirmation can not be empty");
    }
    //if password and confirm password are valid then add to variable
    else if($validator->validPassword($_POST['password'], $_POST['confirmPassword'])){
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    }
    //else set error that password's don't match
    else{
      $this->_f3->set('errors["registerPass"]', "Passwords do not match");
      //echo var_dump($this->_f3->get('errors'));
    }
  
    //hash IP address with md5()
    $ipaddress = md5($ipaddress);
    
  
    //Bind the parameters
    //$statement->bindParam('', $_POST[''], PDO::PARAM_STR);
    $statement->bindParam(':username', $username, PDO::PARAM_STR);
    $statement->bindParam(':userEmail', $email, PDO::PARAM_STR);
    $statement->bindParam(':userIp', $ipaddress, PDO::PARAM_STR);
    $statement->bindParam(':userPassword', $password, PDO::PARAM_STR);
  
    //Execute
    if(empty($this->_f3->get('errors'))){
      // echo '<script>alert("statement executed")</script>';
      $statement->execute();
    }
    return true;
  }
  
  /**
   *
   * Function original posted at: https://stackoverflow.com/a/2031935
   * @return string
   */
  function get_ip()
  {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
      if (array_key_exists($key, $_SERVER) === true) {
        
        foreach (explode(',', $_SERVER[$key]) as $ip){
          $ip = trim($ip); // just to be safe
          
          if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return $ip;
          }
          
        }
      }
    }
  }
}