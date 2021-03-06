<?php

function registerUser($f3, $dbh)
{
  //Define the query
  $sql = "INSERT INTO users (username, user_email, user_ip, user_password)
        VALUES (:username, :userEmail, :userIp, :userPassword);";

  //Prepare the statement
  $statement = $dbh->prepare($sql);

  //set and validate variables

  //retreive username
  $username = $_POST['username'];
  if(!filter_var($username, FILTER_SANITIZE_STRING)){
    $f3->set('errors["registerName"]', "Username is not valid");
    return false;
    
  }

  //retreive email
  $email = $_POST['email'];
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $f3->set('errors["registerEmail"]', "Email is not valid");
    return false;
  }

  //retreive password from post data and then hash with BCRYPT if valid
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];
  
  if(validPassword($password, $confirmPassword)){
    $password = password_hash($password, PASSWORD_BCRYPT);
  } else{
    $f3->set('errors["registerPass"]', "Passwords do not match");
    return false;
  }

  //get user IP Address and then hash with md5()
  $ipaddress = get_ip_address();
  $ipaddress = md5($ipaddress);
  

  //Bind the parameters
  //$statement->bindParam('', $_POST[''], PDO::PARAM_STR);
  $statement->bindParam(':username', $username, PDO::PARAM_STR);
  $statement->bindParam(':userEmail', $email, PDO::PARAM_STR);
  $statement->bindParam(':userIp', $ipaddress, PDO::PARAM_STR);
  $statement->bindParam(':userPassword', $password, PDO::PARAM_STR);

  //Execute
  $statement->execute();
  return true;
}

/**
 *
 * Function original posted at: https://stackoverflow.com/a/2031935
 * @return string
 */
function get_ip_address()
{
  foreach (
    array(
      'HTTP_CLIENT_IP',
      'HTTP_X_FORWARDED_FOR',
      'HTTP_X_FORWARDED',
      'HTTP_X_CLUSTER_CLIENT_IP',
      'HTTP_FORWARDED_FOR',
      'HTTP_FORWARDED',
      'REMOTE_ADDR'
    ) as $key) {
    if (array_key_exists($key, $_SERVER) === true) {
      
      foreach (explode(',', $_SERVER[$key]) as $ip){
        $ip = trim($ip); // just to be safe
        if (
          filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE |
            FILTER_FLAG_NO_RES_RANGE) !== false
        ) {
          return $ip;
        }
      }
    }
  }
}