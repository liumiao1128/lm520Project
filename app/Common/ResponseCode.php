<?php
namespace App\Common;

/**
 * 返回码定义
 */
class ResponseCode{
    // 成功
    const SUCCESS               = 1;
    const SYS_ERROR             = 0;
    // 系统级别错误 1001-1999
    const HTTP_ERROR            = 1000;
    const INVALID_URI           = 1001;  # 无效的请求地址
    const PARAM_ERROR           = 1002;  # 参数错误
    const MYSQL_INSERT_ERROR    = 1003;  # 数据库插入失败
    const MYSQL_UPDATE_ERROR    = 1004;  # 数据库更新失败
    const HEADER_ERROR          = 1005;  # header验证失败
    const SIGNATURE_LACK        = 1006;  # 签名参数缺失
    const SIGNATURE_ERROR       = 1007;  # 签名错误
    const TOKEN_LACK            = 1008;  # token参数缺失
    const TIME_LACK             = 1009;  # time参数缺失
    const STAFF_ERROR           = 1010;  # 用户不存在
    const HEADER_LACK           = 1011;  # header参数缺失
    const MYSQL_QUERY_ERROR     = 1012;  # 数据查询异常
    const TOKEN_EXPIRED_ERROR   = 1013;  # token 过期


    const BUSINESS_ERROR        = 2000;  #业务级错误

    const TASK_ERROR        = 3000;  #定时任务错误

}
