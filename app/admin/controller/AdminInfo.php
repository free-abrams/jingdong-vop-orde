<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\business\Admin\AdminInfoBusiness;
use app\common\lib\ErrorCode;
use app\middleware\JWT;
use think\response\Json;

class AdminInfo extends BaseController
{
    protected $middleware = [
        JWT::class
    ];

    public function info(): Json
    {
        $LoginBusiness = new AdminInfoBusiness();
        $resp = $LoginBusiness->info();
        return show(ErrorCode::$errors['success'], $resp);
    }

        # 重制密码
    public function resetPassword(): Json
    {
        $LoginBusiness = new AdminInfoBusiness();
        $resp = $LoginBusiness->resetPassword(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    public function save(): Json
    {
        $LoginBusiness = new AdminInfoBusiness();
        $resp = $LoginBusiness->save(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }
}