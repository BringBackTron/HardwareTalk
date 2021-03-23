<?php

/**
 * Class Logout logs the user out
 *
 * Class Logout logs the user out and then redirects them using fat-free
 * back to the homepage.
 *
 * @author George McMullen
 * @author Shawn Potter
 * @version 1.0
 */
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
    //destroy the session
    session_destroy();

    //reroute the user
    $this->_f3->reroute("/");
  }
}
