<?php

/**
 * Class Validator checks for valid inputs
 *
 * Class Validator checks for valid inputs in the password and email inputs
 * on the registration form.
 *
 * @author George McMullen
 * @author Shawn Potter
 * @version 1.0
 */
class Validator
{

  /**
   * Checks the two password inputs
   *
   * Checks the two password inputs against each other from the register form
   *
   * @param $pass string password input from form
   * @param $confirmPass string confirmPassword input from form
   * @return bool true if the two input fields are equal, else false
   */
  public function validPassword($pass, $confirmPass)
  {
    //if the two passwords match then return true
    return $pass == $confirmPass;
  }


  /**
   * Checks to see if the email entered is valid
   *
   * Checks to see if the email entered is valid by using filter_var and
   * FILTER_VALIDATE_EMAIL. Returns a boolean based on whether the email
   * is valid or invalid
   *
   * @param $email string email input from the register form
   * @return bool true if the email is valid, else false
   */
  public function validEmail($email) {
    //validate email using filter_var
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    }
    else {
      return false;
    }
  }
}
