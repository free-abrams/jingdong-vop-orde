<?php

namespace app\common\model\mysql\System;

use app\common\model\mysql\BaseModel;

class SystemSaleAmount extends BaseModel
{
    public function getAmountAttr($value): int
    {
        return (int)$value;
    }

    public function getNumAttr($value): int
    {
        return (int)$value;
    }

    public function getOrderNumAttr($value): int
    {
        return (int)$value;
    }

    public function getUserNumAttr($value): int
    {
        return (int)$value;
    }
}