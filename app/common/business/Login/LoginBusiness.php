<?php

namespace app\common\business\Login;

use app\common\business\BaseBusiness;
use app\common\exception\BusinessException;
use app\common\model\mysql\Admin\Admin;
use thans\jwt\facade\JWTAuth;

class LoginBusiness extends BaseBusiness
{

    # 登录
    public function sign_in($param): array
    {
        $Admin = new Admin();

        $where   = [];
        $where[] = ["username", "=", $param["username"]];
        $where[] = ["status", "=", 1];

        $field = ['id', 'username', 'password'];

        $info = $Admin->getInfo($where, $field);
        if (empty($info)) {
            throw new BusinessException('user_is_empty');
        }

        if (!($info["password"] == md5(md5(trim($param["password"])) . config('app.salt')))) {
            throw new BusinessException('user_password_err');
        }

        //生成token
        $data["token"] = 'Bearer ' . JWTAuth::builder(['admin_id' => $info["id"]]);

        return $data;
    }

    #
    public function roles_and_menu(): array
    {
        $Admin = new Admin();

        $where   = [];
        $where[] = ['id', '=', self::getAdminId()];
        $field   = ['id', 'username', 'password', 'nickname', 'avatar'];
        $admin   = $Admin->where($where)->with(['roles' => function ($q) {
            $q->getQuery()->where('status', '=', 1)->with(['rules' => function ($q) {
                $q->getQuery()->order('sort', 'ASC');
            }]);

        }])->field($field)->find();

        if (!$admin) {
            throw new BusinessException('rbac_menu_err');
        }

        $arr = $admin->toArray();

        $data             = [];
        $data['nickname'] = $admin->nickname;
        $data['avatar']   = $admin->avatar;
        $data['rules']    = $admin->getRules();
        $data['menu']     = $admin->getMenus();

        return $data;
    }

}
