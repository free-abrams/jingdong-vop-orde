<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\business\Label\LabelBusiness;
use app\common\lib\ErrorCode;
use think\response\Json;
use app\middleware\JWT;

class Label extends BaseController
{
    protected $middleware = [JWT::class];

    public function list(): Json
    {
        $business = (new LabelBusiness());
        $resp = $business->lists(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    public function save(): Json
    {
        $business = (new LabelBusiness());
        $resp = $business->save(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    public function users(): Json
    {
        $business = (new LabelBusiness());
        $resp = $business->users(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    # 详情
    public function info(): Json
    {
        $business = (new LabelBusiness());
        $resp = $business->show(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    # 删除
    public function remove(): Json
    {
        $business = (new LabelBusiness());
        $resp                   = $business->delete(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }
}
