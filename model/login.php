<?php
class Login
{
  private $_dbh;
  private $_f3;
  function __construct($dbh, $f3){
    $this->_dbh = $dbh;
    $this->_f3 = $f3;
  }
  function logInUser()
  {
    global $validator;
  
    $result="";
    //Define the query
    $sql = "SELECT user_password FROM users WHERE user_email = :email;";
    
    //Prepare the statement
    $statement = $this->_dbh->prepare($sql);
    
    if(empty($_POST['email'])) {
      $this->_f3 -> set('errors["loginEmail"]', "Email can not be empty");
    } else if($validator->validEmail($_POST['email'])) {
      //Bind the parameters
      $statement->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
      //Execute
      $statement->execute();
      $result = $statement->fetch(PDO::FETCH_ASSOC);
      
      echo var_dump($_POST['password']);
    } else {
      $this->_f3->set('errors["loginEmail"]', "Email is not valid");
      echo var_dump($this->_f3->get('errors'));
    }
    
    /*print_r($result['user_password']);
    
    $password = $result[0];*/
  
    /*echo password_verify($_POST['password'], $result['user_password']);
    password_verify($_POST['password'], $result['user_password'] );*/
    
    if(empty($_POST['password'])) {
      $this->_f3 -> set('errors["loginPass"]', "Password can not be empty");
    } else if(password_verify($_POST['password'], $result['user_password'])){
      echo '<script>alert("Passwords Match")</script>';
    } else {
      $this->_f3 -> set('errors["loginPass"]', "Invalid Password");
      echo "<br>".var_dump($this->_f3->get('errors'));
      return false;
    }
    
  }
}