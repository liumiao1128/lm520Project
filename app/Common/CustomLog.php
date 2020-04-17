<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 22/6/2018
 * Time: 11:37 AM
 */

namespace App\Common;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CustomLog
{
    /**
     * 记录日志
     * @param string $keyword
     * @param string $msg
     */
    private static function addInfo($keyword = '', $msg = '')
    {
        $log = new Logger($keyword);
        $logDir = storage_path('logs/' . env('APP_NAME') . '/' . $keyword);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $log->pushHandler(new StreamHandler($logDir . '/' . $keyword . '_' . date('Y-m-d') . '.log', Logger::DEBUG));
        $log->addInfo($msg);
    }

    /**
     * 记录定时任务日志
     * @param string $msg
     */
    public static function taskinfo($msg = '')
    {
        self::addInfo('task', $msg);
    }

    /**
     * 记录查询日志
     * @param string $msg
     */
    public static function queryinfo($msg = '')
    {
        self::addInfo('query', $msg);
    }

    /**
     * 调度相关日志
     * @param string $msg
     */
    public static function dispatchinfo($msg = '')
    {
        self::addInfo('dispatch', $msg);
    }

    /**
     * 队列相关日志
     * @param string $msg
     */
    public static function queueinfo($msg = '')
    {
        self::addInfo('queue', $msg);
    }

    /**
     * 返回日志很长或者频繁日志
     * @param string $msg
     */
    public static function longinfo($msg = '')
    {
        self::addInfo('long', $msg);
    }
}