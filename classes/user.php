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
     * Returns the User's ID number
     *
     * Returns the User's ID number from the $_userID variable
     *
     * @return integer for user ID
     */
    public function getUserID()
    {
      return $this -> _userID;
    }

    /**
     * Returns the username
     *
     * Returns the username from the $_username variable
     *
     * @return string the user's name
     */
    public function getUsername()
    {
      return $this -> _username;
    }

    /**
     * Returns int value of user's role
     *
     * Returns int value of the user's role from the $_userRole variable
     *
     * @return integer of $_userRole
     */
    public function getUserRole()
    {
      return $this -> _userRole;
    }

    /**
     * Returns User's IP address
     *
     * Returns User's IP Address hashed with md5. This will only be seen by admins.
     *
     * @return string md5 hashed string
     */
    public function getUserIP()
    {
      return $this -> _userIP;
    }
  }