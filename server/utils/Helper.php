<?php

namespace app\utils;

class Helper
{

    function getEmailOTP()
    {
        $randomNumber = rand(100000, 999999);
        return $randomNumber;
    }
}
