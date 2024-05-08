<?php

namespace app\utils;

    class Validation{

        public static function isSize(string $item, int $min){
            return isset($item) && strlen(trim($item)) >= $min;
        }

        public static function isNumber(int $value): bool{
            return $value !== 0 && is_int($value);
        }

        public static function isFullName(string $fullname): bool{
            $arr = explode(' ', $fullname);
            return (
                count($arr) >= 2 && count($arr) <= 3 &&
                strlen(trim($arr[0])) > 4 && strlen(trim($arr[1])) > 4
            );
        }


        public static function isEmail(string $email_address): bool{
            
            return (
                isset($email_address) &&
                strlen(trim($email_address)) > 11
            );


        }


        public static function isPhone(string $phone_number): bool{
            return (
                isset($phone_number) && strlen(trim($phone_number)) >= 11 
                && strlen(trim($phone_number)) <=  16
            );
        }
    }

?>