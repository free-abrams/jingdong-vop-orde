<?php

namespace app\common\model\mysql\Admin;

use app\common\model\mysql\BaseModel;
use app\common\model\Traits\RbacCheck;
use think\model\concern\SoftDelete;
use think\model\relation\BelongsToMany;

class Admin extends BaseModel
{
    use SoftDelete;
    use RbacCheck;
    protected $table = 'admin';

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

    public $hidden = ['updated_at','deleted_at','del_status'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Roles::class, AdminToRoles::class, 'role_id', 'admin_id');
    }
}
