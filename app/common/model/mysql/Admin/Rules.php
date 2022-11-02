<?php

namespace app\common\model\mysql\Admin;

use app\common\model\mysql\BaseModel;

class Rules extends BaseModel
{
    protected $table = 'system_rules';
    # 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    # 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime = 'deleted_at';
    protected $defaultSoftDelete = null;


    # 设置json类型字段
    protected $json = [];
    # 设置JSON数据返回数组
    protected $jsonAssoc = true;

    public $hidden = ['created_at','updated_at','deleted_at','del_status'];
}
