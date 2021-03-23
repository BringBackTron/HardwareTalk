<?php

/**
 * Class Community
 *
 * @author George McMullen
 * @author Shawn Potter
 * @version 1.0
 */
class Community
{
  private $_dbh;
  private $_f3;

  /**
   * Community constructor.
   *
   * @param $dbh object database object
   * @param $f3 object fat-free object
   */
  public function __construct($dbh, $f3)
  {
    $this->_dbh =  $dbh;
    $this->_f3 = $f3;
  }

  /**
   * Loads the specified post
   *
   * Loads the specified post so that the post can be viewed with the comments
   * on the comments view
   *
   * @param $postID integer the id for the post
   */
  public function viewPost($postID)
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

      $statement->bindParam(":post_id", $postID, PDO::PARAM_INT);

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
  }

  /**
   * Grabs all posts in a specified community
   *
   * Grabs all posts in a specified community by passing in the
   * community id and executing a sql query. The results are stored
   * and set in a fat-free variable to be displayed on the
   * community view.
   *
   * @param $communityID integer community id number
   */
  public function viewPosts($communityID)
  {
    $sql = "SELECT * FROM posts WHERE community_id = :community_id ORDER BY post_creation_date DESC ";
    if($statement = $this->_dbh->prepare($sql)){
      
      /* Debug */
      // echo "statement prepared";
      
      $statement->bindParam(":community_id", $communityID, PDO::PARAM_INT);

      if($statement->execute()){

        $results = $statement->fetchAll();

        $this->_f3->set("posts", $results);
      }
    }
  }

  /**
   * Submits a post to a community
   *
   * Submits a post to a community by executing a sql statement that inserts
   * a new row into the posts table in the database. If media is empty
   * post_type is set to 0.
   *
   * @param $communityID integer id of the community
   * @param $subject string subject title of the post
   * @param $text string text of the post
   * @param $media string url link for the media (optional)
   */
  public function submitPost($communityID, $subject, $text, $media)
  {
    //sql statement
    $sql = "INSERT INTO posts(community_id, user_poster_id, post_type, post_subject, post_text, post_media) 
            VALUES (:community_id, :user_poster_id, :post_type, :post_subject, :post_text, :post_media)";
    if($statement = $this->_dbh->prepare($sql)) {

      //check to see if $media is empty and set post_type
      if(empty($media)){
        $post_type = 0;
      } else {
        $post_type = 1;
      }

      //bind params
      $statement->bindParam(":community_id", $communityID, PDO::PARAM_INT);
      $statement->bindParam(":user_poster_id", $_SESSION['user']->getUserID(), PDO::PARAM_INT);
      $statement->bindParam(":post_type", $post_type, PDO::PARAM_INT);
      $statement->bindParam(":post_subject", $subject, PDO::PARAM_STR);
      $statement->bindParam(":post_text", $text, PDO::PARAM_STR);
      $statement->bindParam(":post_media", trim($media), PDO::PARAM_STR);

      //if the statement can execute
      if($statement->execute()) {

        //update post count in community
        $this->updatePostCounts($communityID);

        //redirect user
        $this->rerouteToSubmittedPost($communityID, $_SESSION['user']->getUserID());

      } else {
        echo "An Error Occured.";
      }
    } else {
      echo "An Error Occured";
    }
  }

  /**
   * Gets all comments associated with a post
   *
   * Gets all comments associated with a post by passing in the community
   * id and executing a sql query. The results are stored and set in a
   * fat-free variable to be displayed on the comments view
   *
   * @param $communityID integer id number of the community
   * @param $postID integer id number of the post
   */
  public function viewComments($communityID, $postID)
  {
    //sql statement
    $sql = "
            SELECT c.*, u.username, u.user_ip
            FROM comments AS c
            INNER JOIN users AS u
            ON (c.commenter_id = u.user_id)
            WHERE community_id = :community_id AND post_id = :post_id 
            ORDER BY c.comment_time
            ";
    //prepare the statement
    if($statement = $this->_dbh->prepare($sql)) {

      //bind the params
      $statement->bindParam(":community_id", $communityID, PDO::PARAM_STR);
      $statement->bindParam(":post_id", $postID, PDO::PARAM_STR);

      //execute the statement
      if($statement->execute()){

        //fetchAll and store in results
        $results = $statement->fetchAll();

        //create a comments variable
        $this->_f3->set("comments", $results);

      } else {
        echo "An Error Occured";
      }

    } else {
      echo "Statement failed to prepare";
    }
  }

  /**
   * Submits a comment on a post
   *
   * Submits a comment on a post executing a sql statement to insert
   * a new row into the comments table on the database.
   *
   * @param $communityID integer id of the community
   * @param $postID integer id of the post
   * @param $text string submitted text from textarea
   */
  public function submitComment($communityID, $postID, $text)
  {
    //sql statement
    $sql = "
            INSERT INTO comments(post_id, community_id, commenter_id, comment_text)
            VALUES (:post_id, :community_id, :commenter_id, :comment_text)
           ";
    if($statement = $this->_dbh->prepare($sql)) {

      //bind params
      $statement->bindParam(":post_id", $postID, PDO::PARAM_INT);
      $statement->bindParam(":community_id", $communityID, PDO::PARAM_INT);
      $statement->bindParam(":commenter_id", $_SESSION['user']->getUserID(), PDO::PARAM_INT);
      $statement->bindParam(":comment_text", $text, PDO::PARAM_STR);

      //execute the statement
      if($statement->execute()) {

        //update post count on post
        $this->updateCommentsCount($postID, $_SESSION['user']->getUserID());

        //redirect user
        $this->_f3->reroute("community/".$communityID."/".$postID);

      } else {
        echo "An Error Occured during execution.";
      }
    } else {
      echo "An error occured during prepare.";
    }
  }

  /**
   * Updates the post count in the communities table
   *
   * Updates the post count in the communities table by one each time a post
   * is submitted
   *
   * @param $communityID integer passes in the id number of the commmunity
   */
  public function updatePostCounts($communityID)
  {
    //sql statement
    $sql = "UPDATE communities
            SET 
                community_posts = community_posts + 1, 
                community_last_commenter_id = :user_id
            WHERE community_id = :community_id";
    //prepare the statement
    if($statement = $this->_dbh->prepare($sql)) {

      //bind the params
      $statement->bindParam(":community_id", $communityID, PDO::PARAM_INT);
      $statement->bindParam(":user_id", $_SESSION['user']->getUserID(), PDO::PARAM_INT);

      //execute the statement
      $statement->execute();
    } else {
      echo "An Error Occured";
    }

  }

  /**
   * Updates the number of comments
   *
   * Updates the number of comments on the posts table in the database and
   * records who the last user to comment was.
   *
   * @param $postID integer id of the post
   * @param $userID integer id of the last commenter
   */
  private function updateCommentsCount($postID, $userID)
  {
    //sql query
    $sql = "UPDATE posts
            SET 
                post_comments = post_comments + 1, 
                post_last_commenter_id = :user_id
            WHERE post_id = :post_id";

    //prepare the statement
    if($statement = $this->_dbh->prepare($sql)) {

      //bind the params
      $statement->bindParam(":post_id", $postID, PDO::PARAM_INT);
      $statement->bindParam(":user_id", $userID, PDO::PARAM_INT);

      //execute the statement
      $statement->execute();
    } else {
      echo "An Error Occured during preperation";
    }
  }

  /**
   * Reroutes the user to the post they just summitted
   *
   * @param $communityID integer id of the community
   * @param $userID integer id of the user
   */
  private function rerouteToSubmittedPost($communityID, $userID)
  {
    //sql query
    $sql =
      "
        SELECT MAX(post_id) 
        FROM posts 
        WHERE community_id = :community_id AND user_poster_id = :user_id
      ";

    //prepare the statement
    if($statement = $this->_dbh->prepare($sql)) {

      //bind the params
      $statement->bindParam(":community_id", $communityID, PDO::PARAM_INT);
      $statement->bindParam(":user_id", $userID, PDO::PARAM_INT);

      //execute the statement
      if($statement->execute()) {

        //fetch the result
        $result = $statement->fetch();

        //save the correct array element
        $result = $result['MAX(post_id)'];

        //reoute the user
        $this->_f3->reroute("community/".$communityID."/".$result);
      }
    }


  }

}