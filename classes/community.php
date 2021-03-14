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
    $sql = "SELECT * FROM posts WHERE community_id = :community_id ORDER BY post_creation_date DESC ";
    if($statement = $this->_dbh->prepare($sql)){
      //echo "statement prepared"; //debug
      $url = $_SERVER['REQUEST_URI'];
      $url = explode('/', $url);
      $community_id = array_pop($url);
      $statement->bindParam(":community_id", $community_id, PDO::PARAM_STR);
    } else {
      //echo "statement failed to prepare"; //debug
    }
    //var_dump($_SERVER['REQUEST_URI']); // debug
    if($statement->execute()){
      //echo "statement executed";
      $results = $statement->fetchAll();
      $now = new DateTime("now");
      foreach ($results as $key => $item){
        $time = $results[$key]['post_creation_date'];
        $time = new DateTime($time, new DateTimeZone('EST'));
        // var_dump($time); // debug
        $time = $time->diff($now);
        $results[$key]['post_creation_date'] = $time->format("%h");
      }
      $this->_f3->set("posts", $results);
    }
    else {
      //echo "statement failed";
    }



    /*echo "\nPDOStatement::errorInfo():\n";
    $arr = $statement->errorInfo();
    print_r($arr);*/

  }
}