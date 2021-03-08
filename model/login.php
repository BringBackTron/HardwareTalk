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
    
    $email = "";
    $password = "";
    
    if(empty(trim($_POST['email']))){
      $this->_f3 -> set('errors["loginEmail"]', "Email can not be empty");
    } else if(!($validator->validEmail(trim($_POST['email'])))){
      $this->_f3 -> set('errors["loginEmail"]', "Invalid Email");
    } else {
      $email = trim($_POST['email']);
    }
    
    if(empty(trim($_POST['password']))){
      $this->_f3 -> set('errors["loginPass"]', "Password can not be empty");
    } else {
      $password = trim($_POST['password']);
    }
    
    if(empty($this->_f3->get('errors'))) {
      $sql = "SELECT user_id, username, user_password FROM users WHERE user_email = :email;";
      
      if($statement = $this->_dbh->prepare($sql)){
        
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        
        if($statement->execute()){
          if($statement->rowCount() == 1){
            $row = $statement->fetch();
            $id = $row['user_id'];
            $username = $row['username'];
            $hashedPassword = $row['user_password'];
            if(password_verify($password, $hashedPassword)){
              session_start();
              $_SESSION['loggedin'] = true;
              $_SESSION['user_id'] = $id;
              $_SESSION['username'] = $username;
              echo '<script>alert("Passwords Match, user logged in")</script>';
              echo "<pre>";
              echo print_r($_SESSION, true);
              echo "</pre>";
            } else{
              $this->_f3 -> set('errors["loginPass"]', "Invalid Password");
            }
          }
        } else {
          $this->_f3->set('errors["accountNotFound"]', "No account found with that Email Address");
        }
        unset($statement);
      }
    }
    unset($_dbh);
    $this->_f3->set('success["loggedin"]', "You have been logged in. You will be redirected.");
    sleep(4);
    $this->_f3->reroute('/');
  }
}