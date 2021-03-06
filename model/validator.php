<?php

function validPassword($pass, $confirmPass)
{
  return $pass == $confirmPass;
}
