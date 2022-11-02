<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\business\Menu\MenuBusiness;
use app\common\lib\ErrorCode;
use app\middleware\AdminLogs;
use app\middleware\JWT;
use app\middleware\RbacRule;
use think\response\Json;

class Menu extends BaseController
{
    protected $middleware = [
        JWT::class,
        RbacRule::class,
        AdminLogs::class,
    ];

    # 菜单
    public function list(): Json
    {
        $MenuBusiness = new MenuBusiness();
        $resp = $MenuBusiness->lists(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    # 菜单保存
    public function save(): Json
    {
        $MenuBusiness = new MenuBusiness();
        $resp = $MenuBusiness->save(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    # 菜单删除
    public function remove(): Json
    {
        $MenuBusiness = new MenuBusiness();
        $resp = $MenuBusiness->remove(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    # 导入菜单
    public function import_menu(): Json
    {
        $MenuBusiness = new MenuBusiness();
        $resp = $MenuBusiness->import_menu(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }
}
