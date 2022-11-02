<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\business\Admin\AdminBusiness;
use app\common\lib\ErrorCode;
use app\middleware\RbacRule;
use think\App;
use think\facade\Db;
use think\response\Json;
use app\middleware\JWT;

/**
 * 账号管理
 */
class Admin extends BaseController
{
    protected $middleware = [
        JWT::class,
        RbacRule::class,
    ];

    # 列表 - 分页&搜索
    public function list(): Json
    {
        $AdminBusiness = new AdminBusiness();
        $resp = $AdminBusiness->adminList(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    # 新增&编辑
    public function save(): Json
    {
        Db::startTrans();
        try {
            $AdminBusiness = new AdminBusiness();
            $res = $AdminBusiness->adminSave(self::$param);
            Db::commit();
            return show(ErrorCode::$errors['success'], $res);
        } catch (\Exception $e) {
            Db::rollback();
            return show(ErrorCode::$errors[$e->getMessage()]);
        }
    }

    # 详情
    public function info(): Json
    {
        $AdminBusiness = new AdminBusiness();
        $res = $AdminBusiness->adminInfo(self::$param);

        return show(ErrorCode::$errors['success'], $res);
    }

    # 删除
    public function remove(): Json
    {
        $AdminBusiness = new AdminBusiness();
        $res = $AdminBusiness->adminRemove(self::$param);

        return show(ErrorCode::$errors['success'], $res);
    }


}
