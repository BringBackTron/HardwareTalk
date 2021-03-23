<?php

  /**
   * Class Admin is a child class of the User class
   *
   * Class Admin is a child class of the User class which provides the additional
   * function of allowing the admin to delete comments in posts and see the hashed
   * IP addresses of users so they can see if someone might using multiple accounts
   * in an abusive way.
   *
   * @author George McMullen
   * @author Shawn Potter
   * @version 1.0
   */
  class Admin extends User
  {

    /**
     * Runs a sql query to delete a single comment
     *
     * Runs a sql query to delete a single comment, based on the id passed into the function
     *
     * @param $commentID integer id of the comment to be deleted
     * @param $dbh object database object
     * @param $postID integer id of the post the comment belongs to
     */
    public function deleteComment($commentID, $dbh, $postID)
    {
      //sql query
      $sql =
        "
          DELETE FROM comments
          WHERE comment_id = :comment_id;
        ";

      //prepare the statement
      if($statement = $dbh->prepare($sql)) {

        //bind the params
        $statement->bindParam(":comment_id", $commentID, PDO::PARAM_INT);

        //execute the statement
        $statement->execute();
      }
      //call the decrementPostCount
      $this->decrementPostCount($dbh, $postID);

    }


    private function decrementPostCount($dbh, $postID)
    {
      //sql query
      $sql =
        "
          UPDATE posts
          SET post_comments = post_comments - 1
          WHERE post_id = :post_id;
        ";

      //prepare the statement
      if($statement = $dbh->prepare($sql)) {

        //bind the params
        $statement->bindParam(":post_id", $postID, PDO::PARAM_INT);

        //execute the statement
        $statement->execute();
      }
    }
  }
