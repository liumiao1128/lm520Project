<?php

namespace App\Common;

use Illuminate\Http\Request;

class CustomAes
{
    static private $key = '9z9timrent201807';   //16字节
    static private $iv = '1234561234567890';    //16字节

    /**
     * @param Request $request
     * 加密
     */
    public static function encrypt($str)
    {
        $encrypted = openssl_encrypt($str, 'aes-128-cbc', self::$key, OPENSSL_RAW_DATA, self::$iv);
        $result_str = base64_encode($encrypted);
        return $result_str;
    }

    /**
     * @param $bizParams  解密参数
     * @return array
     * 解密
     */
    public static function decrypt($str)
    {

        $result_str = openssl_decrypt(base64_decode($str), 'aes-128-cbc', self::$key, OPENSSL_RAW_DATA, self::$iv);
        return $result_str;
    }
}