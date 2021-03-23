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
  function viewPost($post_id)
  {
    $sql = "
            SELECT p.*, u.username 
            FROM posts AS p 
            INNER JOIN users AS u
            ON (p.user_poster_id = u.user_id)
            WHERE post_id = :post_id";
    if($statement = $this->_dbh->prepare($sql)){

      /* Debug */
      // echo "statement prepared";

      $statement->bindParam(":post_id", $post_id, PDO::PARAM_INT);

      if($statement->execute()){

        /* Debug */
        // echo "statement executed";

        $result = $statement->fetch();
      }
        $this->_f3->set("post", $result);
    } else {
      echo "An Error Occured Executing the Statement";
      /* Debug */
      /*
      echo "\nPDOStatement::errorInfo():\n";
      $arr = $statement->errorInfo();
      print_r($arr);
      */
    }

    /* Currently a Debug statement, change to something else like an error page */
    /*
    else {
      echo "statement failed to prepare";
    }
    */




  }
  function viewPosts($community_id)
  {
    $sql = "SELECT * FROM posts WHERE community_id = :community_id ORDER BY post_creation_date DESC ";
    if($statement = $this->_dbh->prepare($sql)){
      
      /* Debug */
      // echo "statement prepared";
      
      /* old method of getting community id */
      // $url = $_SERVER['REQUEST_URI'];
      // $url = explode('/', $url);
      // $community_id = array_pop($url);
      
      $statement->bindParam(":community_id", $community_id, PDO::PARAM_INT);
      
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

  function viewComments($community_id, $post_id)
  {
    $sql = "
            SELECT c.*, u.username 
            FROM comments AS c
            INNER JOIN users AS u
            ON (c.commenter_id = u.user_id)
            WHERE community_id = :community_id AND post_id = :post_id 
            ORDER BY comment_thumbs
            ";
    if($statement = $this->_dbh->prepare($sql)){

      /* Debug */
      // echo "statement prepared";

      $statement->bindParam(":community_id", $community_id, PDO::PARAM_STR);
      $statement->bindParam(":post_id", $post_id, PDO::PARAM_STR);

    }
    /* Currently a Debug statement, change to something else like an error page */

    else {
      echo "<br>statement failed to prepare";
    }



    if($statement->execute()){

      /* Debug */
      // echo "statement executed";

      $results = $statement->fetchAll();
      $this->_f3->set("comments", $results);

      /* Debug */
      // echo "<pre>";
      // echo print_r($results, true);
      // echo "</pre>";
    }
    /* Debug */

    else {
      echo "<br>An Error Occured";
    }



    /* Debug */

    // echo "\n<br>PDOStatement::errorInfo():\n";
    // $arr = $statement->errorInfo();
    // print_r($arr);


  }

  function submitComment($community_id, $post_id, $text)
  {
    $sql = "
            INSERT INTO comments(post_id, community_id, commenter_id, comment_text)
            VALUES (:post_id, :community_id, :commenter_id, :comment_text)
           ";
    if($statement = $this->_dbh->prepare($sql)) {

      //bind params
      $statement->bindParam(":post_id", $post_id, PDO::PARAM_INT);
      $statement->bindParam(":community_id", $community_id, PDO::PARAM_INT);
      $statement->bindParam(":commenter_id", $_SESSION['user_id'], PDO::PARAM_INT);
      $statement->bindParam(":comment_text", $text, PDO::PARAM_STR);

      if($statement->execute()) {
        //update post count on post
        $this->updateCommentsCount($post_id, $_SESSION['user_id']);

        //redirect user

      } else {
        echo "An Error Occured during execution.";
      }
    } else {
      echo "An error occured during prepare.";
    }
  }

  function submitPost($community_id, $subject, $text, $media)
  {
    $sql = "INSERT INTO posts(community_id, user_poster_id, post_type, post_subject, post_text, post_media) 
            VALUES (:community_id, :user_poster_id, :post_type, :post_subject, :post_text, :post_media)";
    if($statement = $this->_dbh->prepare($sql)) {
      /* Debug */
      // echo "statement prepared";

      if(empty($media)){
        $post_type = 0;
      } else {
        $post_type = 1;
      }

      $statement->bindParam(":community_id", $community_id, PDO::PARAM_INT);
      $statement->bindParam(":user_poster_id", $_SESSION['user_id'], PDO::PARAM_INT);
      $statement->bindParam(":post_type", $post_type, PDO::PARAM_INT);
      $statement->bindParam(":post_subject", $subject, PDO::PARAM_STR);
      $statement->bindParam(":post_text", $text, PDO::PARAM_STR);
      $statement->bindParam(":post_media", $media, PDO::PARAM_STR);

      if($statement->execute()) {
        //update post count in community
        $this->updatePostCounts($community_id);

        //redirect user
        $this->rerouteToSubmittedPost($community_id, $_SESSION['user_id']);

      } else {
        echo "An Error Occured.";
      }


    } else {
      echo "An Error Occured";
    }
  }

  /**
   * Helper method for submitPost() to reroute the user to the post they just made.
   * @param $communityId
   */
  public function rerouteToSubmittedPost($communityId, $userId)
  {
    $sql =
      "
        SELECT MAX(post_id) 
        FROM posts 
        WHERE community_id = :community_id AND user_poster_id = :user_id
      ";
    if($statement = $this->_dbh->prepare($sql)) {

      $statement->bindParam(":community_id", $communityId, PDO::PARAM_INT);
      $statement->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);

      if($statement->execute()) {
        $result = $statement->fetch();

        $result = $result['MAX(post_id)'];

        /* Debug */
        // echo "<pre>";
        // echo print_r($result, true);
        // echo "</pre>";

        $this->_f3->reroute("community/".$communityId."/".$result);
      }
    }


  }


  /**
   * Updates the post count in the communities table
   *
   * Updates the post count in the communities table by one each time a post is submitted
   *
   * @param $community_id integer passes in the id number of the commmunity
   */
  function updatePostCounts($community_id)
  {
    $sql = "UPDATE communities
            SET 
                community_posts = community_posts + 1, 
                community_last_commenter_id = :user_id
            WHERE community_id = :community_id";
    if($statement = $this->_dbh->prepare($sql)) {
      /* Debug */
      // echo "statement prepared";

      $statement->bindParam(":community_id", $community_id, PDO::PARAM_INT);
      $statement->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);

      $statement->execute();
    } else {
      echo "An Error Occured";
    }

  }
  
  //TODO: write function to add thumbs to post
  function addThumbs(){

  }

  private function updateCommentsCount($post_id, $user_id)
  {
    $sql = "UPDATE posts
            SET 
                post_comments = post_comments + 1, 
                post_last_commenter_id = :user_id
            WHERE post_id = :post_id";

    if($statement = $this->_dbh->prepare($sql)) {
      /* Debug */
      // echo "statement prepared";

      $statement->bindParam(":post_id", $post_id, PDO::PARAM_INT);
      $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);

      $statement->execute();
    } else {
      echo "An Error Occured during preperation";
    }
  }

}