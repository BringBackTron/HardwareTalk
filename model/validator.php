<?php
class Validator
{
  function validPassword($pass, $confirmPass)
  {
    return $pass == $confirmPass;
  }
  
  function validEmail($email) {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return true;
    }
    else{
      return false;
    }
  }
}
