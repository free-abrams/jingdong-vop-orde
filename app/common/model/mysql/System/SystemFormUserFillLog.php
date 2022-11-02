<?php

namespace app\common\model\mysql\System;

use app\common\model\mysql\BaseModel;
use app\common\model\mysql\User\UserBase;
use think\model\relation\HasOne;

class SystemFormUserFillLog extends BaseModel
{
    public function userBase(): HasOne
    {
        return $this->hasOne(UserBase::class, 'user_id', 'user_id');
    }
}