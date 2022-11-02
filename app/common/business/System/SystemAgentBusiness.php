<?php

namespace app\common\business\System;

use app\common\exception\BusinessException;
use app\common\model\mysql\SingleCommission;
use app\common\model\mysql\System\SystemAgent;

class SystemAgentBusiness
{
    public function index($param): array
    {
        $where = [];
        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }
        if (!empty($param['search_name'])) {
            $where[] = ['status', 'like', '%'.$param['status'].'%'];
        }
        return (new SystemAgent())->pageList($where, $param['page'], $param['page_size']);
    }

    public function show($param): ?array
    {
        $where[] = ['id', '=', $param['id']];

        $model = (new SystemAgent());
        $model->with = ['commission'];

        return $model->getInfo($where);
    }

    public function save($param): bool
    {
        if ($param['id']) {
            return $this->update($param);
        } else{
            return $this->create($param);
        }
    }

    public function change_status($param): bool
    {
        $model = (new SystemAgent())->find($param['id']);
        if (empty($model)) {
            return true;
        }
        $update['status'] = $param['status'];
        $res = $model->save($update);
        if (!$res) {
            throw new BusinessException('add_err');
        }

       return true;
    }

    public function create($param): bool
    {
        $add = $this->getArr($param);

        $res = (new SystemAgent())->save($add);
        if (!$res) {
            throw new BusinessException('add_err');
        }
        // 分销设置
        $this->commission($param);
        return true;
    }

    public function update($param): bool
    {
        $model = (new SystemAgent())->find($param['id']);
        if (empty($model)) {
            return true;
        }
        $update = $this->getArr($param);

        $res = $model->save($update);
        if (!$res) {
            throw new BusinessException('add_err');
        }

        // 分销设置
       $this->commission($param);

       return true;
    }

    public function getAgentsWhenCreating(): array
    {
        return (new SystemAgent())->getList([], null, 'id,name', 'a', null, 'id ASC');
    }

    public function destroy($param): bool
    {
        $model = (new SystemAgent())->find($param['id']);
        if (empty($model)) {
            return true;
        }

        return $model->delete();
    }

    public function commission($param): bool
    {
        if (empty($param['commission'])) {
            return true;
        }
        $where = [];
        $where[] = ['table_id', '=', $param['id']];
        $where[] = ['type', '=', SystemAgent::class];
        $commission = (new SingleCommission())->where($where)->find();
        if (empty($commission)) {
            $add = [];
            $add['table_id'] = $param['id'];
            $add['type'] = SystemAgent::class;
            $add['rules'] = $param['commission'];
            $res = (new SingleCommission())->save($add);
        } else {
            $update['rules'] = $param['commission'];
            $res = $commission->save($update);
        }

        if (!$res) {
            throw new BusinessException('edit_err');
        }

        return true;
    }

    /**
     * @param $param
     * @return array
     */
    private function getArr($param): array
    {
        $update = [];
        $update['name'] = $param['name'];
        $update['status'] = $param['status'];
        $update['detail'] = $param['detail'];
        $update['price'] = $param['price'];
        $update['payment'] = $param['payment'];
        $update['system_form_id'] = $param['system_form_id'];
        $update['share_post'] = $param['share_post'];
        $update['show_children_phone'] = $param['show_children_phone'];
        $update['show_children_activity'] = $param['show_children_activity'];
        $update['show_parent_phone'] = $param['show_parent_phone'];
        $update['show_parent_name'] = $param['show_parent_name'];
        $update['discount'] = $param['discount'];
        $update['discount_param'] = $param['discount_param'];
        return $update;
    }
}