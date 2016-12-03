<?php

/**
 * Created by PhpStorm.
 * User: Julia
 * Date: 2016-09-29
 * Time: 11:25 PM
 */
//General class for validation. Contains attributes and methods that can be reused in other forms.


class Validation
{
    public static $latitude="";
    public static $longitude="";

    //Checks whether the value is set. Can be reused in another methods before actually making validation
    public static function isRequired($value){
        $flag=false;
        if (empty($value)||!isset($value)){


        }else{
            $flag=true;
        }
        return $flag;


    }

    //Checks whether the value is string using isRequired method
    public static function isString($value)
    {
        $flag = false;
        if (self::isRequired($value)) {
            if (ctype_alpha($value)) {

                $flag = true;
            }
        }
        return $flag;
    }



    //Checks whether a string is an email
    public static function validEmail($value)
    {
        $flag=false;
        if (self::isRequired($value)) {

            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $flag = true;
            }else{
         //       echo "<script type='text/javascript'>alert('Please enter valid email')</script>";
            }
        }

        return $flag;
    }

    //Checks whether a string is a number
    public static function validNumber($value){
        $flag = false;
        if (self::isRequired($value)) {
            if(is_numeric($value)) {
                $flag = true;
            }
        }
      //  echo "<script type='text/javascript'>alert('Please enter valid number')</script>";
        return $flag;
    }

    //The password length should be at least 8 characters
    public static function validPassword($value){
        $flag = false;
        if (self::isRequired($value)) {
            if(strlen($value)>=8) {
                $flag = true;
            }
        }
      //  echo "<script type='text/javascript'>alert('The password should be more than 8 characters')</script>";
        return $flag;
    }


    //Check if the address exists
    public static function validAddress($value){
        $flag = false;
        if (self::isRequired($value)) {
            $prepAddr = str_replace(' ','+',$value);

            $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
            $output= json_decode($geocode, true);
            if ($output['status'] == 'OK'){
                self::$latitude =$output['results'][0]['geometry']['location']['lat'];
                self::$longitude = $output['results'][0]['geometry']['location']['lng'];

                $flag=true;}}

        return $flag;
    }
}


?>


