<?php
class Validator
{

  /**
   * TODO: write phpdoc
   * @param $pass
   * @param $confirmPass
   * @return bool
   */
  function validPassword($pass, $confirmPass)
  {
    //if the two passwords match then return true
    return $pass == $confirmPass;
  }


  /**
   * TODO: write phpdoc
   * @param $email
   * @return bool
   */
  function validEmail($email) {
    //validate email using filter_var
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    }
    else{
      return false;
    }
  }
}
