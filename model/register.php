<?php
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require files
require $_SERVER['DOCUMENT_ROOT'].'/../ht-db-config.php';

//Define the query
$sql = "INSERT INTO users (username, user_email, user_ip, user_password)
        VALUES (:username, :userEmail, :userIp, :userPassword);";
  
//Prepare the statement
$statement = $dbh->prepare($sql);

//set variables

//retreive username
$username = $_POST['username'];

//retreive email
$email = $_POST['email'];

//retreive password from post data and then hash with sha1() if valid
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

if(validPassword($password, $confirmPassword)){
  $password = sha1($password);
}

//get user IP Address and then hash with sha1()
$ipaddress = get_ip_address();
$ipaddress = sha1($ipaddress);


//validate variables

//Bind the parameters
//$statement->bindParam('', $_POST[''], PDO::PARAM_STR);
$statement->bindParam(':username', $username, PDO::PARAM_STR);
$statement->bindParam(':userEmail', $email, PDO::PARAM_STR);
$statement->bindParam(':userIp', $ipaddress, PDO::PARAM_STR);
$statement->bindParam(':userPassword', $password, PDO::PARAM_STR);

//Execute
$statement->execute();
  
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