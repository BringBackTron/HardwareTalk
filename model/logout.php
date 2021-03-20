<?php
class Logout
{
  private $_f3;

  /**
   * Logout constructor.
   * @param $_f3
   */
  public function __construct($f3)
  {
    $this -> _f3 = $f3;
  }

  function logout($_f3)
  {
    session_destroy();
    $this->_f3->reroute("/");
  }
}
