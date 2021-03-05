<?php
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require files
require $_SERVER['DOCUMENT_ROOT'].'/../ht-db-config.php';

//Define the query
$sql = "";

//Prepare the statement
$statement = $dbh->prepare($sql);

//Bind the parameters
$statement->bindParam('', $_POST[''], PDO::PARAM_STR);

//Execute
$statement->execute();
