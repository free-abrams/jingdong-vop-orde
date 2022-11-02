<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\business\Login\LoginBusiness;
use app\common\lib\ErrorCode;
use think\response\Json;

/**
 * 登录
 */
class Login extends BaseController
{
    # 登录
    public function sign_in(): Json
    {
        $LoginBusiness = new LoginBusiness();
        $resp = $LoginBusiness->sign_in(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    # 获取当前用户权限
    public function get_member_info(): Json
    {
        $LoginBusiness = new LoginBusiness();
        $resp = $LoginBusiness->roles_and_menu();

        return show(ErrorCode::$errors['success'], $resp);
    }
}
