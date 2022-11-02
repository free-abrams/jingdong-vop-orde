<?php

namespace app\common\model\mysql\Admin;

use think\model\Pivot;

class RoleToRules extends Pivot
{
    protected $prefix = 'system';
}
