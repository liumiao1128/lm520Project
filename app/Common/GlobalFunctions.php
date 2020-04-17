<?php

use Ramsey\Uuid\Uuid;
use App\Common\ResponseCode;
use Illuminate\Support\Facades\Log;

/**
 * 生成uuid
 * @return mixed
 */
function getUuid()
{
    $temp = Uuid::uuid1();
    return $temp->getHex();
}

/**
 * api响应
 * @param string $data
 * @param int $code
 * @param string $message
 * @return \Illuminate\Http\JsonResponse
 */
function apiResponse($data = null, $code = ResponseCode::SUCCESS, $message = 'success')
{
    $response = array(
        'code' => $code,
        'msg' => $message,
        'time' => date('Y-m-d H:i:s'),
        'data' => $data,
    );

    return response()->json($response);
}

/**
 * 检查签名的合法性
 * @param $data
 * @return int
 */
function checkSignature($data)
{
    $result_arr = [
        'code' => ResponseCode::SYS_ERROR,
        'msg' => ''
    ];

    if (empty($data['signature'])) {
        $result_arr = [
            'code' => ResponseCode::SIGNATURE_LACK,
            'msg' => '缺少签名参数'
        ];
        return $result_arr;
    }
    if (empty($data['time'])) {
        $result_arr = [
            'code' => ResponseCode::TIME_LACK,
            'msg' => '缺少time参数'
        ];
        return $result_arr;
    }

    $time = substr($data['time'], 0, 10);//有可能传的是13位的时间戮
    if ($time < time() - 2 * 60) {//2分钟前的请求，视为不合法
        $result_arr = [
            'code' => ResponseCode::TIME_NO_VALID,
            'msg' => '请求过期'
        ];
        return $result_arr;
    }

    $signature = $data['signature'];
    unset($data['signature']);
    unset($data['time']);

    $sign_string = '';
    ksort($data);//排序
    //拼接参数
    foreach ($data as $key => $val) {
        if (!is_object($val) && !is_array($val)) {//不是对象，上传图片或者文件字段，不参与签名
            $sign_string .= $key . '=' . $val . '&';
        }
    }
    //加一个相互约定的字符串
    $secret_str = '0f977b6090bc11e89de47ef7fbe91ebc';

    $sign_string = $sign_string . 'anniePortalKey='.$secret_str;
    //转换为小写
    $sign_string = strtolower_extended($sign_string);//strtolower($sign_string);

    //md5加密签名得到signature
    $server_signature = base64_encode(md5($sign_string));
    $result_arr = [
        'code' => ResponseCode::SUCCESS,
        'msg' => ''
    ];

    if ($signature != $server_signature) {
        $result_arr = [
            'code' => ResponseCode::SIGNATURE_ERROR,
            'msg' => '签名错误'
        ];
        return $result_arr;
    } else {
        $result_arr = [
            'code' => ResponseCode::SUCCESS,
            'msg' => ''
        ];
        return $result_arr;
    }
}

function dLog($message, $content = array())
{
    if (!env('LOG_DEBUG', false)) {
        return false;
    }

    return _Monolog('debug', $message, $content);
}

function iLog($message, $content = array())
{
    return _Monolog('info', $message, $content);
}

function eLog($message, $content = array())
{
    return _Monolog('error', $message, $content);
}

function apiUniqId()
{
    return isset($GLOBALS['API_ACCESS_GUID']) ? $GLOBALS['API_ACCESS_GUID'] : '-';
}

function _Monolog($func, $message, $content = array())
{

    $apiUniqId = apiUniqId();
    $message = "{$apiUniqId} {$message}";

    return \Log::$func($message, $content);
}
