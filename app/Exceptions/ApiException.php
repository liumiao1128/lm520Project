<?php
namespace App\Exceptions;

/**
 * Api自定义异常基类
 * Api下的所有自定义异常都从此类或其子类继承
 */
class ApiException extends \Exception{
    function __construct($msg='')
    {
        parent::__construct($msg);
    }
}
