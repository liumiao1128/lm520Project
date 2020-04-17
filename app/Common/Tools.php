<?php
/**
 * 这个公共函数文件，用于存放一些小工具函数（不和任何框架代码耦合）
 * /
 *
 * /**
 * 产生随机字符
 * @param $length 长度
 * @param $numeric 标识    0:字母和数字     1:只有数字
 */
function yn_random($length, $numeric = 0)
{
    PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
    $seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    $hash = '';
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 安全的64编码
 * 解决出现左斜杠“/”问题
 * @param $string
 * @return mixed|string
 */
function urlsafe_b64encode($string)
{
    $data = base64_encode($string);
    $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    return $data;
}

/**
 * 安全的64解码
 * 解决出现左斜杠“/”问题
 * @param $string
 * @return string
 */
function urlsafe_b64decode($string)
{
    $data = str_replace(array('-', '_'), array('+', '/'), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}

/**
 * 验证客户端是mobile还是pc
 * @return boolean
 */
function dstrpos($string, &$arr)
{
    if (empty($string)) return false;
    foreach ((array)$arr as $v) {
        if (strpos($string, $v) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * 验证客户端是mobile还是pc
 * @param $http_user_agent $_SERVER['HTTP_USER_AGENT']
 * @return boolean true=手机 false=pc
 */
function yn_checkmobile($http_user_agent)
{
    static $mobilebrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
        'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
        'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
        'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
        'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
        'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
        'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
    $useragent = strtolower($http_user_agent);
    if (dstrpos($useragent, $mobilebrowser_list)) {
        return TRUE;
    }
//        $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
//        if(dstrpos($useragent, $brower)) return FALSE;
    return false;
}

/**
 * 文件size换算
 * $size 单位 为 B
 * @param unknown_type $size
 */
function yn_get_file_size($size = 0)
{
    $rzt = '0KB';
    if ($size > 0 && $size < 1024) {
        $rzt = $size . 'B';
    } else if ($size > 1024 && $size < (1024 * 1024)) {
        $rzt = number_format($size / 1024, 2) . 'KB';
    } else if ($size > (1024 * 1024) && $size < (1024 * 1024 * 1024)) {
        $rzt = number_format(($size / 1024) / 1024, 2) . 'M';
    } else if ($size > (1024 * 1024 * 1024)) {
        $rzt = number_format((($size / 1024) / 1024) / 1024, 2) . 'G';
    }
    return $rzt;
}

/**
 * 得到每一天00:00：00的时间戳（传入年月日格式的时间戳）
 * @param int $start_date
 * @param int $end_date
 * @return array $every_day
 */
function getEveryDayTimestampByZero($start_date, $end_date)
{
    $start_date = mktime(12, 0, 0, date('m', $start_date), date('d', $start_date), date('Y', $start_date));
    $days = getDays($start_date, $end_date);
    $every_day = [];
    $first_date = $start_date;
    for ($i = 0; $i < $days; $i++) {
        if(date('H', $first_date) < 12){
            $date = date('Y-m-d', $first_date);
            $start_date = strtotime("$date - 1 days");
        }
        $every_day[] = strtotime(date('Y-m-d', $start_date + $i * 3600 * 24));
    }
    return $every_day;
}

/**
 * 得到每一天00:00：00的时间戳（传入年月日 时分秒格式的时间戳）
 * @param int $start_date
 * @param int $end_date
 * @return array $every_day
 */
function getEveryDayTimestamp($start_date, $end_date)
{
    $days = getDays($start_date, $end_date);
    $every_day = [];
    $first_date = $start_date;
    for ($i = 0; $i < $days; $i++) {
        if(date('H', $first_date) < 12){
            $date = date('Y-m-d', $first_date);
            $start_date = strtotime("$date - 1 days");
        }
        $every_day[] = strtotime(date('Y-m-d', $start_date + $i * 3600 * 24));
    }
    return $every_day;
}

/**
 * 得到天数
 * @param string $start_date
 * @param string $end_date
 * @return int $days
 */
function getDays($start_date, $end_date)
{
    $end_date += 12 * 3600;
    $days = ceil(($end_date - $start_date) / (3600 * 24));
    return intval($days);
}

/**
 * 相加
 * @param $left_operand
 * @param $right_operand
 * @param $scale
 * @return string
 */
function mathadd($left_operand, $right_operand, $scale = null){
    return (string)round(bcadd($left_operand, $right_operand, $scale+1), $scale);
}
/**
 * 相减
 * @param $left_operand
 * @param $right_operand
 * @param $scale
 * @return string
 */
function mathsub($left_operand, $right_operand, $scale = null){
    return (string)round(bcsub($left_operand, $right_operand, $scale+1), $scale);
}
/**
 * 相乘
 * @param $left_operand
 * @param $right_operand
 * @param $scale
 * @return string
 */
function mathmul($left_operand, $right_operand, $scale = null){
    return (string)round(bcmul($left_operand, $right_operand, $scale+1), $scale);
}
/**
 * 相除
 * @param $left_operand
 * @param $right_operand
 * @param $scale
 * @return string
 */
function mathdiv($left_operand, $right_operand, $scale = null){
    return (string)round(bcdiv($left_operand, $right_operand, $scale+1), $scale);
}

function get_msectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}

//自定义函数手机号隐藏中间四位
function replace_phone($str)
{
    if (empty($str)) return '';
    $resstr = substr_replace($str, '****', 3, 4);
    return $resstr;
}

//自定义函数姓名隐藏名字
function replace_name($name)
{
    if (empty($name)) return '';
    $last_name  = mb_substr($name, 0,1, 'utf-8');                                    #姓
    $first_name = mb_substr($name, 1,null, 'utf-8');                                 #名
    $len = mb_strlen($first_name,'utf-8');                                            #名字长度
    $star = generate_star($len);
    return $last_name.$star;
}

//自定义函数身份证隐藏部分
function replace_identify_card($str)
{
    if (empty($str)) return '';
    $resstr = substr_replace($str, '********', 6, 8);
    return $resstr;
}

function generate_star($num){
    $str = '';
    for($i=0;$i<$num;$i++){
        $str.='*';
    }
    return $str;
}

/**
 * 敏感数据加密
 * @param $str
 */
function data_encrypt($str)
{
    return \App\Common\CustomAes::encrypt($str);
}

/**
 * 敏感数据解密
 * @param $str
 */
function data_decrypt($str)
{
    return \App\Common\CustomAes::decrypt($str);
}

/**
 * 13位时间戮转日期格式 xxxx-xx-xx hh:mm:ss.xxx
 * @param $time
 * @return string
 */
function get_microtime_format($time)
{
    $date = substr($time,0,10);
    $micro_time = substr($time,10);
    $result_date = date("Y-m-d H:i:s",$date).'.'.$micro_time;
    return $result_date;
}

/**
 * 字符转小写_
 * @param $utf8_string
 * @return mixed|string
 */
function strtolower_extended( $utf8_string )
{
    $additional_replacements    = array
    ( "ǅ"    => "ǆ"        //   453 ->   454
    , "ǈ"    => "ǉ"        //   456 ->   457
    , "ǋ"    => "ǌ"        //   459 ->   460
    , "ǲ"    => "ǳ"        //   498 ->   499
    , "Ϸ"    => "ϸ"        //  1015 ->  1016
    , "Ϲ"    => "ϲ"        //  1017 ->  1010
    , "Ϻ"    => "ϻ"        //  1018 ->  1019
    , "ᾈ"    => "ᾀ"        //  8072 ->  8064
    , "ᾉ"    => "ᾁ"        //  8073 ->  8065
    , "ᾊ"    => "ᾂ"        //  8074 ->  8066
    , "ᾋ"    => "ᾃ"        //  8075 ->  8067
    , "ᾌ"    => "ᾄ"        //  8076 ->  8068
    , "ᾍ"    => "ᾅ"        //  8077 ->  8069
    , "ᾎ"    => "ᾆ"        //  8078 ->  8070
    , "ᾏ"    => "ᾇ"        //  8079 ->  8071
    , "ᾘ"    => "ᾐ"        //  8088 ->  8080
    , "ᾙ"    => "ᾑ"        //  8089 ->  8081
    , "ᾚ"    => "ᾒ"        //  8090 ->  8082
    , "ᾛ"    => "ᾓ"        //  8091 ->  8083
    , "ᾜ"    => "ᾔ"        //  8092 ->  8084
    , "ᾝ"    => "ᾕ"        //  8093 ->  8085
    , "ᾞ"    => "ᾖ"        //  8094 ->  8086
    , "ᾟ"    => "ᾗ"        //  8095 ->  8087
    , "ᾨ"    => "ᾠ"        //  8104 ->  8096
    , "ᾩ"    => "ᾡ"        //  8105 ->  8097
    , "ᾪ"    => "ᾢ"        //  8106 ->  8098
    , "ᾫ"    => "ᾣ"        //  8107 ->  8099
    , "ᾬ"    => "ᾤ"        //  8108 ->  8100
    , "ᾭ"    => "ᾥ"        //  8109 ->  8101
    , "ᾮ"    => "ᾦ"        //  8110 ->  8102
    , "ᾯ"    => "ᾧ"        //  8111 ->  8103
    , "ᾼ"    => "ᾳ"        //  8124 ->  8115
    , "ῌ"    => "ῃ"        //  8140 ->  8131
    , "ῼ"    => "ῳ"        //  8188 ->  8179
    , "Ⅰ"    => "ⅰ"        //  8544 ->  8560
    , "Ⅱ"    => "ⅱ"        //  8545 ->  8561
    , "Ⅲ"    => "ⅲ"        //  8546 ->  8562
    , "Ⅳ"    => "ⅳ"        //  8547 ->  8563
    , "Ⅴ"    => "ⅴ"        //  8548 ->  8564
    , "Ⅵ"    => "ⅵ"        //  8549 ->  8565
    , "Ⅶ"    => "ⅶ"        //  8550 ->  8566
    , "Ⅷ"    => "ⅷ"        //  8551 ->  8567
    , "Ⅸ"    => "ⅸ"        //  8552 ->  8568
    , "Ⅹ"    => "ⅹ"        //  8553 ->  8569
    , "Ⅺ"    => "ⅺ"        //  8554 ->  8570
    , "Ⅻ"    => "ⅻ"        //  8555 ->  8571
    , "Ⅼ"    => "ⅼ"        //  8556 ->  8572
    , "Ⅽ"    => "ⅽ"        //  8557 ->  8573
    , "Ⅾ"    => "ⅾ"        //  8558 ->  8574
    , "Ⅿ"    => "ⅿ"        //  8559 ->  8575
    , "Ⓐ"    => "ⓐ"        //  9398 ->  9424
    , "Ⓑ"    => "ⓑ"        //  9399 ->  9425
    , "Ⓒ"    => "ⓒ"        //  9400 ->  9426
    , "Ⓓ"    => "ⓓ"        //  9401 ->  9427
    , "Ⓔ"    => "ⓔ"        //  9402 ->  9428
    , "Ⓕ"    => "ⓕ"        //  9403 ->  9429
    , "Ⓖ"    => "ⓖ"        //  9404 ->  9430
    , "Ⓗ"    => "ⓗ"        //  9405 ->  9431
    , "Ⓘ"    => "ⓘ"        //  9406 ->  9432
    , "Ⓙ"    => "ⓙ"        //  9407 ->  9433
    , "Ⓚ"    => "ⓚ"        //  9408 ->  9434
    , "Ⓛ"    => "ⓛ"        //  9409 ->  9435
    , "Ⓜ"    => "ⓜ"        //  9410 ->  9436
    , "Ⓝ"    => "ⓝ"        //  9411 ->  9437
    , "Ⓞ"    => "ⓞ"        //  9412 ->  9438
    , "Ⓟ"    => "ⓟ"        //  9413 ->  9439
    , "Ⓠ"    => "ⓠ"        //  9414 ->  9440
    , "Ⓡ"    => "ⓡ"        //  9415 ->  9441
    , "Ⓢ"    => "ⓢ"        //  9416 ->  9442
    , "Ⓣ"    => "ⓣ"        //  9417 ->  9443
    , "Ⓤ"    => "ⓤ"        //  9418 ->  9444
    , "Ⓥ"    => "ⓥ"        //  9419 ->  9445
    , "Ⓦ"    => "ⓦ"        //  9420 ->  9446
    , "Ⓧ"    => "ⓧ"        //  9421 ->  9447
    , "Ⓨ"    => "ⓨ"        //  9422 ->  9448
    , "Ⓩ"    => "ⓩ"        //  9423 ->  9449
    //, "𐐦"    => "𐑎"       // 66598 -> 66638
    //,"𐐧"    => "𐑏"      // 66599 -> 66639
    );

    $utf8_string    = strtolower($utf8_string);//mb_strtolower( $utf8_string, "UTF-8");

    $utf8_string    = strtr( $utf8_string, $additional_replacements );

    return $utf8_string;
}

/**
 * n天n分钟
 * @param $realEndTime //实际结束时间
 * @param $realBeginTime //订单开始时间
 * @param $dayEndTime //订单预计结束时间
 * @param $rentDays //订单预订天数
 * @param $overMinute //超时分钟数
 * @return mixed|string
 */
function getRealRentDays($realEndTime, $realBeginTime, $dayEndTime, $rentDays, $overMinute)
{
    if(empty($realEndTime)){
        return '进行中';
    }
    if ($realEndTime > $dayEndTime) {
        return $rentDays . '天' . $overMinute . '分钟';
    } else {
        return ceil(($realEndTime - $realBeginTime) / 86400) . '天';
    }
}

function getRealIp()
{
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $realip = $realip[0];
        } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $realip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
            $realip = $realip[0];
        } else if (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    return $realip;
}
