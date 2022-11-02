<?php

namespace app\common\model\mysql\Admin;

use app\common\model\mysql\BaseModel;
use think\model\relation\BelongsToMany;

class Roles extends BaseModel
{
    protected $table = 'system_roles';

    public $hidden = ['created_at','updated_at','deleted_at','del_status'];

    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(Rules::class, RoleToRules::class, 'rule_id','role_id');
    }
}
