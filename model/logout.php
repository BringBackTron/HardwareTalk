<?php
class Logout
{
  private $_f3;

  /**
   * Logout constructor.
   * @param $f3 object fat-free object
   */
  public function __construct($f3)
  {
    $this -> _f3 = $f3;
  }

  /**
   * Destroys the session to log out the user
   *
   * Destroys the session to log out the user and then redirects
   * them to the homepage.
   */
  public function logout()
  {
    session_destroy();
    $this->_f3->reroute("/");
  }
}
