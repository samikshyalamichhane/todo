<?php

namespace App\Helpers;

class ResponseHelper{
    static function successHandler($data=null, $message=null, $status_code=null)
    {
        return response()->json(['payload'=> $data, 'message'=>$message, 'status_code'=>$status_code]);
    }

    static function errorHandling($message=null, $status_code=null)
    {
        return response()->json(['message'=>$message, 'status_code'=>$status_code]);
    }
}