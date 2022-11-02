<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\business\News\NewsBusiness;
use app\common\lib\ErrorCode;
use think\response\Json;

class News extends BaseController
{
    public function list(): Json
    {
        $business = (new NewsBusiness());
        $resp = $business->lists(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    public function save(): Json
    {
        $business = (new NewsBusiness());
        $resp = $business->save(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    # 详情
    public function info(): Json
    {
        $business = (new NewsBusiness());
        $resp = $business->show(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    # 删除
    public function remove(): Json
    {
        $business = (new NewsBusiness());
        $resp                   = $business->delete(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }
}