<?php

namespace app\utils;

    class InvestmentHelper{
        public static function getROI($amount, $percent){
            return ($amount + ($amount * ($percent / 100)));            
        }
    }

?>