<?php

namespace app\common\business\Menu;

use app\common\business\BaseBusiness;
use app\common\exception\BusinessException;
use app\common\lib\Tree;
use app\common\model\mysql\Admin\Admin;
use app\common\model\mysql\Admin\Rules;
use think\facade\Db;

class MenuBusiness extends BaseBusiness
{
    public function lists($param): array
    {
        $model      = (new Rules());
        $where[]    = ['is_menu', '=', 1];
        $origin_arr = $model->getList($where, $limit = null, $field = true, $alias = 'a', $join = null, $order = 'sort ASC');
        return Tree::arr2tree($origin_arr, 'id', 'p_id');
    }

    public function save($param): bool
    {
        Admin::clearRuleAndMenu();
        if ($param['id']) {
            return $this->update($param);
        }
        return $this->create($param);
    }

    private function create($param): bool
    {
        unset($param['id']);
        $param['is_menu'] = 1;
        return (new Rules())->save($param);
    }

    private function update($param): bool
    {
        $model = (new Rules())->find($param['id']);
        if (!empty($model) && !$model->save($param)) {
            throw new BusinessException('edit_err');
        }
        return true;
    }

    public function remove($param): bool
    {
        $model = (new Rules())->find($param['id']);
        if (!empty($model) && !$model->delete()) {
            throw new BusinessException('del_err');
        }
        return true;
    }

    public function import_menu($param): bool
    {
        Admin::clearRuleAndMenu();
        $Rules = new Rules();
        $tableName = $Rules->getTable();

        Db::query('TRUNCATE `' . $tableName . '`');

        $index = 0;
        $menu  = [];
        foreach ($param as $k => $item) {

            $item['id'] = ++$index;

            $children = [];
            if (!empty($item['children'])) {
                $children = $item['children'];
                unset($item['children']);
            }

            $two_children = [];
            foreach ($children as &$v) {
                $v['p_id'] = $item['id'];
                $v['id']   = $index += 1;

                if (!empty($v['children'])) {
                    foreach ($v['children'] as &$vv) {
                        $vv['p_id'] = $v['id'];
                    }
                    $two_children = $v['children'];
                    unset($v['children']);
                }
            }

            foreach ($two_children as &$t) {
                $t['id'] = $index += 1;
            }

            $menu[] = $item;
            unset($v);
            foreach ($children as $v) {
                $menu[] = $v;
            }
            unset($t);
            foreach ($two_children as $t) {
                $menu[] = $t;
            }

        }

        # 获取导入数据
        $datas = [];
        foreach ($menu as $v) {
            $data = [
                'id'          => $v['id'],
                'p_id'        => $v['p_id'] ?? 0,
                'title'       => $v['meta']['title'] ?? '',
                'name'        => $v['name'] ?? '',
                'icon'        => $v['meta']['icon'] ?? '',
                'path'        => $v['path'] ?? '',
                'rule'        => $v['name'] ?? '',
                'is_menu'     => 1,
                'component'   => $v['component'] ?? '',
                'hidden'      => !empty($v['hidden']) ? 1 : 0,
                'always_show' => !empty($v['always_show']) ? 1 : 0,
                'redirect'    => $v['redirect'] ?? '',
                'no_cache'    => !empty($v['no_cache']) ? 1 : 0,
                'active_menu' => $v['name']['active_menu'] ?? '',
            ];

            $Rules = new Rules();
            $Rules->save($data);
        }

        return true;
    }
}
