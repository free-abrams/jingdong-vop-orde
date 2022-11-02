<?php

namespace app\common\model\mysql\System;

use app\common\model\mysql\BaseModel;
use think\model\concern\SoftDelete;

class SystemLabel extends BaseModel
{
    use SoftDelete;
    protected $connection = 'myt_unified_user';

    # 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime = 'deleted_at';
    protected $defaultSoftDelete = null;

        # 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    public $append = [];
    public $hidden = ['deleted_at', 'del_status'];
}