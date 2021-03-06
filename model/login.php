<?php

function logInUser($dbh, $f3)
{
  $username = "";
  $password = "";
  
  if(!filter_var($_POST['username'], FILTER_SANITIZE_STRING)){
    $f3->set('errors["registerName"]', "Username is not valid");
    return false;
  }
  
  
  //Define the query
  $sql = "SELECT username user_password FROM users WHERE username = :username;";
  
  //Prepare the statement
  $statement = $dbh->prepare($sql);
  
  //Bind the parameters
  $statement->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
  
  //Execute
  $statement->execute();
  
  $statement->bindColumn(1, $username);
  $statement->bindColumn(2, $password);
  
  return password_verify($_POST['password'], $password);
}
