<?php

  class User{
    private $_userID;
    private $_username;
    private $_userRole;
    private $_userSubbedCommunities;
  
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
    public function setUserID($userID)
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
    public function setUsername($username)
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
    private function getUserSubbedCommunities()
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
    
    
  }