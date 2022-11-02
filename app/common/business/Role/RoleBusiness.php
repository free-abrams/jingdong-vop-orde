<?php

namespace app\common\business\Role;

use app\common\business\BaseBusiness;
use app\common\exception\BusinessException;
use app\common\lib\Tree;
use app\common\model\mysql\Admin\Admin;
use app\common\model\mysql\Admin\Roles;
use app\common\model\mysql\Admin\Rules;

class RoleBusiness extends BaseBusiness
{
    public function lists($param): array
    {
        $where = [];
        if ($param['status']) {
            $where[] = ['status', '=', $param['status']];
        }
        return (new Roles())->pageList($where, $param['page'], $param['page_size']);
    }

    public function create($param): bool
    {
        $model = (new Roles());

        $where   = [];
        $where[] = ['name', '=', $param['name']];
        $res     = $model->getInfo($where);

        if ($res) {
            throw new BusinessException('repeat_err');
        }

        $res = $model->save($param);

        if (!$res) {
            throw new BusinessException('add_err');
        }
        $res = $model->rules()->attach($param['rules']);
        if (!$res) {
            throw new BusinessException('add_err');
        }

        return true;
    }

    public function update($param): bool
    {
        $model = (new Roles());

        $where   = [];
        $where[] = ['id', '=', $param['id']];

        $data = $model->where($where)->with('rules')->find();
        $data->rules()->detach();
        $res = $data->rules()->attach($param['rules']);

        if (!$res) {
            throw new BusinessException('edit_err');
        }

        $where   = [];
        $where[] = ['name', '=', $param['name']];
        $where[] = ['id', '<>', $param['id']];
        $res     = $model->getInfo($where);

        if ($res) {
            throw new BusinessException('repeat_err');
        }
        $update           = [];
        $update['name']   = $param['name'];
        $update['status'] = $param['status'];
        $res              = $model->save($update);

        if (!$res) {
            throw new BusinessException('edit_err');
        }

        Admin::clearRuleAndMenu();

        return true;
    }

    public function show($param): array
    {
        $where[] = ['id', '=', $param['id']];
        $tree    = (new Rules())->field('id,p_id,name,title as label')->select();
        $role    = (new Roles())->where($where)->find();
        if (empty($role)) {
            $data['role']      = [];
            $data['has_rules'] = [];
        } else {
            $data['role']      = $role->toArray();
            $data['has_rules'] = array_column($role->rules->toArray(), 'id');
        }
        $data['all_rules'] = Tree::arr2tree($tree->toArray(), 'id', 'p_id', 'children');
        return $data;
    }

    public function delete($param): bool
    {
        $where[] = ['id', '=', $param['id']];
        $model   = (new Roles())->where($where)->find();
        if ($model->delete() === false) {
            throw new BusinessException('del_err');
        }

        return true;
    }
}
