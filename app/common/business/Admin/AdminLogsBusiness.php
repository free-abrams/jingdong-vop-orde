<?php

namespace app\common\business\Admin;

use app\common\business\BaseBusiness;
use app\common\model\mysql\Admin\AdminLogs;

class AdminLogsBusiness extends BaseBusiness
{
    public function save($param): bool
    {
        $model = (new AdminLogs())->save($param);
        return true;
    }
}
