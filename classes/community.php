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
  function viewPosts($id)
  {
    $sql = "SELECT * FROM posts WHERE community_id = :community_id ORDER BY post_creation_date DESC ";
    if($statement = $this->_dbh->prepare($sql)){
      
      /* Debug */
      // echo "statement prepared";
      
      /* old method of getting community id */
      // $url = $_SERVER['REQUEST_URI'];
      // $url = explode('/', $url);
      // $community_id = array_pop($url);
      
      $statement->bindParam(":community_id", $id, PDO::PARAM_STR);
      
    }
    /* Currently a Debug statement, change to something else like an error page */
    /*
    else {
      echo "statement failed to prepare";
    }
    */
    
    
    if($statement->execute()){
      
      /* Debug */
      // echo "statement executed";
      
      $results = $statement->fetchAll();
      $now = new DateTime("now");
      foreach ($results as $key => $item){
        $time = $results[$key]['post_creation_date'];
        $time = new DateTime($time, new DateTimeZone('EST'));
  
        /* Debug */
        // var_dump($time);
        
        $time = $time->diff($now);
        $results[$key]['post_creation_date'] = $time->format("%h");
      }
      $this->_f3->set("posts", $results);
    }
    /* Debug */
    /*
    else {
      //echo "statement failed";
    }
    */
  
  
    /* Debug */
    /*
    echo "\nPDOStatement::errorInfo():\n";
    $arr = $statement->errorInfo();
    print_r($arr);
    */

  }
  
  //TODO: write function for submitting posts
  function submitPost(){
  
  }
  
  //TODO: write function to add thumbs to post
  function addThumbs(){
  
  }
}