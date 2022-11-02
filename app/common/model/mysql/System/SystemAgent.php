<?php

namespace app\common\model\mysql\System;

use app\common\model\mysql\BaseModel;
use app\common\model\mysql\SingleCommission;
use think\model\relation\HasOne;

class SystemAgent extends BaseModel
{
    protected $connection = 'myt_unified_user';

    # 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime = 'deleted_at';
    protected $defaultSoftDelete = null;

        # 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    # 设置json类型字段
    protected $json = ['share_link', 'discount_param'];

    # 设置JSON数据返回数组
    protected $jsonAssoc = true;

    public function commission(): HasOne
    {
        $where = [];
        $where[] = ['type', '=', self::class];
        return $this->hasOne(SingleCommission::class, 'table_id', 'id')->where($where);
    }
}