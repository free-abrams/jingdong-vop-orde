<?php

namespace app\common\business;

use app\common\exception\BusinessException;
use app\common\model\mysql\System\SystemConfig;
use thans\jwt\facade\JWTAuth;

class BaseBusiness
{

    protected static function getUserId()
    {
        $payload = JWTAuth::getPayload();
        return $payload['user_id']->getValue();
    }

    protected static function getAdminId()
    {
        $payload = JWTAuth::getPayload();
        return $payload['admin_id']->getValue();
    }

    protected static function getOpenid()
    {
        $payload = JWTAuth::getPayload();
        return $payload['open_id']->getValue();
    }

    function checkoutMaxCommission(array $commission): bool
    {
        $where = [];
        $where[] = ['key', '=', 'maxCommissionPercent'];
        $max_commission_percent = (int)(new SystemConfig)->getInfo($where)['value'];
        foreach ($commission as $v ) {
            if (!isset($v['rules'])) {
                throw new BusinessException('undefined array key in rules');
            }
            $sum = array_sum($v['rules']);
            if ($sum > $max_commission_percent) {
                throw new BusinessException('total percent must less than '.$max_commission_percent);
            }
        }
        return true;
    }
}
