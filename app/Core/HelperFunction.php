<?php


namespace App\Core;

use App\tbl_intern_attend;
use App\TblInternProjects;

class HelperFunction
{

    public static function attendance($no)
    {
        $intern = tbl_intern_attend::where('att_intern',$no)->where('att_holiday','0')->count();

        return $intern;
    }
      public static function project($pro)
    {
        $project =TblInternProjects::where('int_proj_internid',$pro)->count();
        

        return $project;
    }
     public static function cproject($pro)
    {
        $cproject =TblInternProjects::where('int_proj_internid',$pro)->where('int_proj_status','1')->count();
        

        return $cproject;
    }
    
    public static function send_sms($to, $message)
    {

      $api_token = "";

      $api_secret = "";

      // $to = "92xxxxxxxxxx";

      $from = "";

      // $message = "Testing SMS";

      $url = "";



      $ch  =  curl_init();

      $timeout  =  30;

      curl_setopt ($ch, CURLOPT_URL, $url);

      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

      $response = curl_exec($ch);

      curl_close($ch);



      if(strstr( $response, 'OK : ' ) || strstr($response ,':1')){

        return true;

      }



      return false;

    }
}
