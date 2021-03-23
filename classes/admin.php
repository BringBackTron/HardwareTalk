<?php
  class Admin extends User{

    /**
     * Runs a sql query to delete a single comment
     *
     * Runs a sql query to delete a single comment, based on the id passed into the function
     *
     * @param $id integer id of the comment to be deleted
     * @param $dbh object database object
     */
    public function deleteComment($id, $dbh)
    {
      $sql =
        "
          DELETE FROM comments
          WHERE comment_id = :comment_id
        ";
      if($statement = $dbh->prepare($sql)) {

        $statement->bindParam(":comment_id", $id, PDO::PARAM_INT);

        $statement->execute();
      }
    }
    
  }
