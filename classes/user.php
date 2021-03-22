<?php

  class User{
    private $_userID;
    private $_username;
    private $_userRole;
    private $_userSubbedCommunities;
    private $_dbh;

    /**
     * User constructor.
     * @param $_dbh
     */
    public function __construct($_dbh)
    {
      $this -> _dbh = $_dbh;
    }


    /**
     * @return mixed
     */
    public function getUserID()
    {
      return $this -> _userID;
    }
  
    /**
     * @param mixed $userID
     */
    private function setUserID($userID)
    {
      $this -> _userID = $userID;
    }
  
    /**
     * @return mixed
     */
    public function getUsername()
    {
      return $this -> _username;
    }
  
    /**
     * @param mixed $username
     */
    private function setUsername($username)
    {
      $this -> _username = $username;
    }
  
    /**
     * @return mixed
     */
    private function getUserRole()
    {
      return $this -> _userRole;
    }
  
    /**
     * @param mixed $userRole
     */
    private function setUserRole($userRole)
    {
      $this -> _userRole = $userRole;
    }
  
    /**
     * @return mixed
     */
    public function getUserSubbedCommunities()
    {
      return $this -> _userSubbedCommunities;
    }
  
    /**
     * @param mixed $userSubbedCommunities
     */
    private function setUserSubbedCommunities($userSubbedCommunities)
    {
      $this -> _userSubbedCommunities = $userSubbedCommunities;
    }
    
    public function updateThumbs($id)
    {
      $total = $this->totalThumbs($id);
      $sql =
        "
            UPDATE users
            SET user_thumbs = :total_thumbs
            WHERE user_id = :user_id
        ";

      if($statement = $this->_dbh->prepare($sql)) {
        $statement->bindParam(":total_thumbs", $total, PDO::PARAM_INT);
        $statement->bindParam(":user_id", $id, PDO::PARAM_INT);
        $statement->execute();
      }

    }
    private function totalThumbs($id)
    {
      return $this->getTotalPostThumbs($id) + $this->getTotalCommentThumbs($id);
    }

    private function getTotalPostThumbs($id)
    {
      $sql =
        "
          SELECT post_thumbs 
          FROM posts 
          WHERE user_poster_id = :user_id
        ";

      if($statement = $this->_dbh->prepare($sql)) {

        $statement->bindParam(":user_id", $id, PDO::PARAM_INT);

        if($statement->execute()) {
          $results = $statement->fetchAll();
          /* Debug */
          // echo "<pre>";
          // echo print_r($results, true);
          // echo "</pre>";

           $total = 0;
           foreach($results as $row){
             $total += $row['post_thumbs'];
           }
           return $total;
        }
      }
      return 0;
    }
    private function getTotalCommentThumbs($id)
    {
      $sql =
        "
            SELECT comment_thumbs 
            FROM comments 
            WHERE commenter_id = :user_id
        ";

      if($statement = $this->_dbh->prepare($sql)) {

        $statement->bindParam(":user_id", $id, PDO::PARAM_INT);

        if($statement->execute()) {

          $results = $statement->fetchAll();

          /* Debug */
          // echo "<pre>";
          // echo print_r($results, true);
          // echo "</pre>";

          $total = 0;
          foreach($results as $row){
            $total += $row['comment_thumbs'];
          }
          return $total;

        }
      }

    }
  }