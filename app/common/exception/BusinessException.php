<?php
/**
 * Created by PhpStorm.
 * User: Jerry
 * Date: 2015/4/16
 * Time: 18:09
 */

namespace app\common\exception;

class BusinessException extends \RuntimeException
{
    # 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0, Throwable $previous = null) {
        # 这里写用户的代码

        # 确保所有变量都被正确赋值
        parent::__construct($message, $code, $previous);
    }
}
