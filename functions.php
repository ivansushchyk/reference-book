<?php

function validateContactData($name,$number)
{      if (strlen($name) < 2 or strlen($name) > 15){
        return "Length of name must be bigger than 2 and less than 15";
      }
      if (strlen($number) !== 10){
        return "Length of number must be 10";
      }
      if (!ctype_digit($number)){
         return "Phone number must be recorded only numerically";
      }

    return null;
}
