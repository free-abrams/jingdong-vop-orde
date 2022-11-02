<?php

namespace app\common\model\mysql\System;

use app\common\model\mysql\BaseModel;
use think\model\concern\SoftDelete;

class SystemBanner extends BaseModel
{
    use SoftDelete;
    public $hidden = [
        'updated_at',
        'deleted_at',
    ];

    # 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    # 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime = 'deleted_at';
    protected $defaultSoftDelete = null;
}