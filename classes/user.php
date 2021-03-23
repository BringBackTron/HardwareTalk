<?php
  class User
  {
    private $_userID;
    private $_username;
    private $_userRole;
    private $_userIP;

    /**
     * User constructor.
     * @param $_userID
     * @param $_username
     * @param $_userRole
     * @param $_userIP
     */
    public function __construct($_userID, $_username, $_userRole, $_userIP)
    {
      $this -> _userID = $_userID;
      $this -> _username = $_username;
      $this -> _userRole = $_userRole;
      $this -> _userIP = $_userIP;
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
    public function getUserRole()
    {
      return $this -> _userRole;
    }

    /**
     * @param mixed $userRole
     */
    public function setUserRole($userRole)
    {
      $this -> _userRole = $userRole;
    }

    /**
     * @return mixed
     */
    public function getUserIP()
    {
      return $this -> _userIP;
    }

    /**
     * @param mixed $userIP
     */
    public function setUserIP($userIP)
    {
      $this -> _userIP = $userIP;
    }




  }