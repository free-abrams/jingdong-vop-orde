<?php

namespace app\common\business\Label;

use app\common\business\BaseBusiness;
use app\common\exception\BusinessException;
use app\common\model\mysql\System\SystemLabel;
use app\common\model\mysql\User\UserToLabel;

class LabelBusiness extends BaseBusiness
{
    public function lists($param): array
    {
        $where = [];
        $where[] = ['name', 'like', '%'.$param['search_name'].'%'];
        return (new SystemLabel())->pageList($where, $param['page'], $param['page_size']);
    }

    public function save($param): bool
    {
        if ($param['id']) {
            return $this->update($param);
        }
        return $this->add($param);
    }

    private function add($param): bool
    {
        $model = (new SystemLabel())->save($param);
        if (!$model) {
            throw new BusinessException('add_err');
        }
        return true;
    }

    private function update($param): bool
    {
        $model = (new SystemLabel())->find($param['id']);

        if (!empty($model) && !$model->save($param)) {
            throw new BusinessException('edit_err');
        }
        return true;
    }

    public function delete($param): bool
    {
        $res = (new SystemLabel())->find($param['id']);

        if ($res && !$res->delete()) {
            throw new BusinessException('sys_err_db');
        }
        return true;
    }

    public function show($param): ?array
    {
        $where[] = ['id', '=', $param['id']];
        return (new SystemLabel())->getInfo($where);
    }

    public function users($param)
    {
        $where[] = ['system_label_id', '=', $param['id']];
        return (new UserToLabel)->with(['user', 'base'])->where($where)->select();
    }
}