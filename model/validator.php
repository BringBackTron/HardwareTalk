<?php
class Validator
{
  //if the two passwords match then return true
  function validPassword($pass, $confirmPass)
  {
    return $pass == $confirmPass;
  }
  
  //validate email using filter_var
  function validEmail($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    }
    else{
      return false;
    }
  }
}
