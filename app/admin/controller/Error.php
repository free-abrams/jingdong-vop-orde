<?php

namespace app\admin\controller;

use app\common\exception\BusinessException;

class Error
{
    public function __call($name, $arguments)
    {
        throw new BusinessException('sys_err_module');
    }
}
