<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApiModel extends Model
{
    //
    public static function ApiValidate($token, $key) {
        $query = DB::table('app_api_key')
            ->select('app_api_key.*')
            ->where('token_key', '=', $token)
            ->where('secret_key', '=', $key)
            ->get();
        //DB::raw("SELECT * FROM app_api_key WHERE token_key='$token' AND secret_key='$key'")->get();
        return count($query);
    }
}
