<?php

namespace App\Common;

require __DIR__ . '/../../vendor/jpush/autoload.php'; // 自动加载
use JPush\Client as Jpush;

class CoustomJPush
{
    //秘钥
    static private $travel_app_key = "";
    static private $travel_master_secret = "";

    //调用的url
    static private $url = "https://api.jpush.cn/v3/push";      //推送的地址

    //推送蜜城出现的消息通知
    //$content 推送的内容。
    //$m_type 推送附加字段的类型(可不填) http,tips,chat....
    //$m_txt 推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
    //$m_time 保存离线时间的秒数默认为一天(可不传)单位为秒
    public static function sendHoneyTravelMessage($content = '',$receiver = 'all', $m_type = '', $m_txt = '', $m_time = 86400)
    {
        $JPUSH_PROD = env('JPUSH_PROD', true);
        $base64 = base64_encode(self::$travel_app_key . ":" . self::$travel_master_secret);
        $header = array("Authorization:Basic $base64", "Content-Type:application/json");
        $data = array();
        $data['platform'] = 'all';          //目标用户终端手机的平台类型android,ios,winphone
        $data['audience'] = $receiver;      //目标用户

        $data['notification'] = array(
            //统一的模式--标准模式
            "alert" => $content,
            //安卓自定义
            "android" => array(
                "alert" => $content,
                "title" => "",
                "builder_id" => 1,
                "extras" => array("type" => $m_type, "txt" => $m_txt)
            ),
            //ios的自定义
            "ios" => array(
                "alert" => $content,
                "badge" => "1",
                "sound" => "default",
                "extras" => array("type" => $m_type, "txt" => $m_txt)
            )
        );

        //苹果自定义---为了弹出值方便调测
        $data['message'] = array(
            "msg_content" => $content,
            "extras" => array("type" => $m_type, "txt" => $m_txt)
        );

        $param = json_encode($data);
        $res = CoustomJPush::push_curl($param, $header);

        if ($res) {       //得到返回值--成功已否后面判断
            return $res;
        } else {          //未得到返回值--返回失败
            return false;
        }
    }

    //推送的Curl方法
    public static function push_curl($param = "", $header = "")
    {
        if (empty($param)) {
            return false;
        }
        $postUrl = self::$url;
        $curlPost = $param;
        $ch = curl_init();                                      //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl);                 //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);                      //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);           // 增加 HTTP Header（头）里的字段
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);                                 //运行curl
        curl_close($ch);
        return $data;
    }

}
