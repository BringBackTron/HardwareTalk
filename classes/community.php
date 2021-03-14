<?php
class Community
{
  private $_dbh;
  private $_f3;
  function __construct($dbh, $f3)
  {
    $this->_dbh =  $dbh;
    $this->_f3 = $f3;
  }
  function viewPosts()
  {
    $sql = "SELECT * FROM posts WHERE community_id = :community_id";
    if($statement = $this->_dbh->prepare($sql)){
      echo "statement prepared"; //debug
    } else {
      echo "statement failed to prepare"; //debug
    }
    var_dump($_SERVER['REQUEST_URI']);
    $url = $_SERVER['REQUEST_URI'];
    $url = explode('/', $url);
    $community_id = array_pop($url);
    $statement->bindParam(":community_id", $community_id, PDO::PARAM_STR);

    if($statement->execute())
      echo "statement executed";
    else
      echo "statement failed";

    $results = $statement->fetchAll();
    $this->_f3->set("posts", $results);
    echo "<pre>";
    echo print_r($this->_f3->get("posts"));
    echo "</pre>";

  }
}