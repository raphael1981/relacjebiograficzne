<?php

namespace App\Helpers;

class CustomHelp{

    static $calendar = [
        "months"=>
            [
                'more',
                null,
                'more',
                'less',
                'more',
                'less',
                'more',
                'more',
                'less',
                'more',
                'less',
                'more'
            ]
    ];

    public static function parseSlugGetId($slug){

        $array = explode('-',$slug);
        $id = $array[key($array)];

        return $id;

    }

    public static function createTokenToAdminEmailAkcept($customer){

        /*
         * Construct $token
         * base 64 app key + last letter customer name +  first letter of customer surname
         * + letter before @ (email) + letter after @ (email) + id
         *
         * all base64_encode()
         *
         */

        $key_string = env('APP_KEY');
        $explode = explode(':', $key_string);
        $key = $explode[1];

        $name_last = substr($customer->name, -1);
        $surname_first = substr($customer->surname, 0,1);

        $email_ex = explode('@', $customer->email);

        $email_before_monkey = substr($email_ex[0], -1);

        $email_after_monkey = substr($email_ex[1], 0,1);

        $token = base64_encode($key.$name_last.$surname_first.$email_before_monkey.$email_after_monkey);

        return $token;

    }


    public static function checkCustomerAkceptToken($token, $customer){

        $tk_to_check = self::createTokenToAdminEmailAkcept($customer);

        if($tk_to_check==$token){
            return true;
        }else{
            return false;
        }

    }

    public static function checkMonthDaysByYearAndMonth($year,$month){

        $leap = self::checkIsLeapYear($year);
        $months_days = self::$calendar['months'];

        $less_more = $months_days[intval($month)-1];
        if(is_null($less_more)){
            if($leap){
                $days = 29;
            }else{
                $days = 28;
            }
        }else{
            switch($less_more){
                case 'more':
                    $days = 31;
                    break;

                case 'less':
                    $days = 31;
                    break;
            }
        }

        return $days;
    }

    public static function checkIsLeapYear($year){

        if((($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0)){
            return true;
        }else{
            return false;
        }

    }


    public static function getBeforeRouteFromFullHttp($url){
        $domain = config('services')['domains']['customers'];
        preg_match("/^(http:\/\/)?([^\/]+)/i",$url, $matches);
        $domain = $matches[1].$domain.'/';
        $route = str_replace($domain,'',$url);
        return $route;
    }

}