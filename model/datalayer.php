<?php

/**
 * Class Datalayer uses functions to help the website display
 *
 * Class DataLayer contains various functions to present aid in the display
 * of the website.
 *
 * @author George McMullen
 * @author Shawn Potter
 * @version 1.0
 */
class DataLayer
{
  private $_f3;
  private $_dbh;

  /**
   * DataLayer constructor.
   * @param $f3 object fat free object
   */
  public function __construct($dbh, $f3)
  {
    $this -> _dbh = $dbh;
    $this -> _f3 = $f3;
  }


  /**
   * Gets the names of the communities
   *
   * Gets the names of the communities to use on the page titles of the
   * dynamic pages
   *
   * @param $communityID integer id of the community
   * @return string
   */
  public function getCommunityName($communityID)
  {
    switch ($communityID){
      case 1:
        return "Gaming";
      case 2:
        return "Setups";
      case 3:
        return "CustomPC";
      case 4:
        return "Laptops";
      case 5:
        return "Phones";
      case 6:
        return "Deals";
      case 7:
        return "News";
      case 8:
        return "Coding";
      case 9:
        return "DIY";
      default:
        return "ERROR";
    }
  }

  /**
   * Updates the database as to the last updated post
   *
   * Updates the database as to the last updated post by getting the latest
   * post in the table for the specified community and updating the
   * community_last_post_id column in the database.
   *
   * @param $communityID integer ID number of the community
   */
  public function updateLastPosted($communityID)
  {
    //get and assign last posted
    $lastPost = $this->getLastPosted($communityID);
    //update community table with lastest post
    $sql = "UPDATE communities SET community_last_post_id = :last_post WHERE community_id = :community_id";
    if($statement = $this->_dbh->prepare($sql)) {
      /* Debug */
      // echo "statement prepared";

      if(empty($lastPost['MAX(post_id)'])){
        $lastPost = 0;
      } else {
        $lastPost = $lastPost['MAX(post_id)'];
      }

      $statement->bindParam(":community_id", $communityID, PDO::PARAM_INT);
      $statement->bindParam(":last_post", $lastPost, PDO::PARAM_INT);

      if($statement->execute()) {
        //do nothing
      } else{

        echo "An Error Occured While Executing";
      }
    } else {
      echo "An Error Occured While Preparing.";
    }
  }

  /**
   * Returns an array containing the last updated post
   *
   * Returns an array containing the last updated post by retreiving the maximum
   * post_id from the post table in the database. Helper method for
   * updateLastPosted
   *
   * @param $communityID integer the id number of the community
   * @return array containing the max value id in the post_id column
   */
  public function getLastPosted($communityID)
  {
    //get latest post_id
    $sql = "SELECT MAX(post_id) FROM posts WHERE community_id = :community_id";
    if($statement = $this->_dbh->prepare($sql)) {
      /* Debug */
      // echo "statement prepared";

      $statement->bindParam(":community_id", $communityID, PDO::PARAM_INT);

      if($statement->execute()) {

        if($statement->rowCount() == 1){
          $result = $statement->fetch();

          /* Debug */
          /*echo "<pre>";
          echo print_r($result, true);
          echo "</pre>";*/

          return $result;
        } else {
          echo "Could not return value";
        }

      } else{
        echo "An Error Occured While Executing";
      }
    } else {
      echo "An Error Occured While Preparing.";
    }
    return null;
  }

  /**
   * Populates the homepage's cards
   *
   * Populates the homepage's cards with the community information and
   * the lastest posts inside that community.
   *
   * @return null if the sql query fails
   */
  public function populateHomePage()
  {
    $sql = "SELECT c.*, p.post_id, p.post_subject, p.post_media, p.post_type
            FROM communities c
            LEFT JOIN posts p 
            ON c.community_last_post_id = p.post_id";

    if($statement = $this->_dbh->prepare($sql)) {

      if($statement->execute()) {
          $results = $statement->fetchAll();

          return $results;
      } else{
        echo "An Error Occured While Executing";
      }
    } else {
      echo "An Error Occured While Preparing.";
    }
    return null;


  }
}