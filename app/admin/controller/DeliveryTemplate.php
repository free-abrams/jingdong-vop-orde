<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\business\Shop\DeliveryTemplateBusiness;
use app\common\lib\ErrorCode;
use think\response\Json;
use app\middleware\JWT;

class DeliveryTemplate extends BaseController
{

    protected $middleware = [JWT::class];

    # 快递模板 - 列表
    public function list(): Json
    {
        $DeliveryTemplateBusiness = new DeliveryTemplateBusiness();
        $resp                          = $DeliveryTemplateBusiness->goodsDeliveryTemplateList(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    # 快递模板 - 保存
    public function save(): Json
    {
        $DeliveryTemplateBusiness = new DeliveryTemplateBusiness();
        $resp                          = $DeliveryTemplateBusiness->goodsDeliveryTemplateSave(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    # 快递模板 - 详情
    public function info(): Json
    {
        $DeliveryTemplateBusiness = new DeliveryTemplateBusiness();
        $resp                          = $DeliveryTemplateBusiness->goodsDeliveryTemplateInfo(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    # 快递模板 - 删除
    public function remove(): Json
    {
        $DeliveryTemplateBusiness = new DeliveryTemplateBusiness();
        $resp                          = $DeliveryTemplateBusiness->goodsDeliveryTemplateRemove(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

    # 快递模板 - 全部
    public function all(): Json
    {
        $DeliveryTemplateBusiness = new DeliveryTemplateBusiness();
        $resp                          = $DeliveryTemplateBusiness->goodsDeliveryTemplateAll(self::$param);
        return show(ErrorCode::$errors['success'], $resp);
    }

}
