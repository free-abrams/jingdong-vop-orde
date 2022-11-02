<?php

namespace app\common\business\Admin;

use app\common\business\BaseBusiness;
use app\common\exception\BusinessException;
use app\common\model\mysql\Admin\Admin;

class AdminInfoBusiness extends BaseBusiness
{
    public function info(): array
    {
        $Admin = new Admin();

        $where = [];
        $where['id'] = self::getAdminId();

        $info = $Admin->getInfo($where);

        if (empty($info)) {
            throw new BusinessException('sys_is_empty');
        }

        return $info;
    }
    // 修改密码
    public function resetPassword($param): bool
    {
        $where = [];
        $where[] = ['id', '=', self::getAdminId()];

        $admin = Admin::where($where)->find();
        $update = [];
        $update['password'] = md5(md5(trim($param["password"])) . config('app.salt'));
        if (!empty($admin) && !$admin->save($update)) {
            throw new BusinessException('err_edit');
        }
        return true;
    }

    public function save($param)
    {
        $model = new Admin();
        $admin = $model->find(self::getAdminId());
        // 更新
        $update = [];
        if ($admin['password'] !== md5(md5(trim($param["password"])) . config('app.salt'))) {
            $update['password'] = md5(md5(trim($param["password"])) . config('app.salt'));
        }
        $update['nickname'] = $param['nickname'];
        $update['status'] = $param['status'];
        $res = $admin->save($update);
        if (!$res) {
            throw new BusinessException('edit_err');
        }
        return true;
    }
}
