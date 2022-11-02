<?php

namespace app\common\business\Admin;

use app\common\business\BaseBusiness;
use app\common\exception\BusinessException;
use app\common\model\mysql\Admin\Admin;
use app\common\model\mysql\Admin\Roles;

class AdminBusiness  extends BaseBusiness
{

    # 列表 - 分页&搜索
    public function adminList($param): array
    {
        $page = $param['page'];
        $page_size = $param['page_size'];

        $where = [];
        $where[] = ['id', '>', 1];

        # 管理端搜索
        if (!empty($param['search_id'])) {
            $id = $param['search_id'];
            $where[] = ['id', '=', $id];
        }

        if (!empty($param['search_nickname'])) {
            $nickname = $param['search_nickname'];
            $where[] = ['nickname', 'like', '%'. $nickname .'%'];
        }

        if (!empty($param['search_username'])) {
            $username = $param['search_username'];
            $where[] = ['username', 'like', '%' . $username . '%'];
        }

        # 用户端搜索

        $field = ['*'];

        $Admin = new Admin();
        $Admin->with = 'roles';

        return $Admin->pageList($where, $page, $page_size, $field);
    }

    # 新增&编辑
    public function adminSave($param): bool
    {
        $model = new Admin();
        $admin = $model->find($param['id']);
        if (empty($admin)) {
            // 新增
            $add['username'] = $param['username'];
            $add['password'] = md5(md5(trim($param["password"])) . config('app.salt'));
            $add['status'] = $param['status'];
            $res = $model->save($add);
            $admin = $model;
            if (!$res) {
                throw new BusinessException('add_err');
            }
        } else {
            // 更新
            $update = [];
            if ($admin['password'] !== md5(md5(trim($param["password"])) . config('app.salt'))) {
                $update['password'] = md5(md5(trim($param["password"])) . config('app.salt'));
            }
            $update['username'] = $param['username'];
            $update['status'] = $param['status'];
            $res = $admin->save($update);
            if (!$res) {
                throw new BusinessException('edit_err');
            }
        }

        // 更新关联角色
        if (!empty($param['roles'])) {
            $admin->roles()->detach();
            $res = $admin->roles()->sync($param['roles']);
            if (!$res) {
                throw new BusinessException('edit_err');
            }
        }
        Admin::clearRuleAndMenu();
        return true;
    }

    # 详情
    public function adminInfo($param): array
    {
        $Admin = new Admin();
        $Admin->with = 'roles';

        $where = [];
        $where['id'] = $param['id'];

        $info = $Admin->getInfo($where);

        $where = [];
        $where[] = ['status', '=', 1];
        $info['allRoles'] = (new Roles())->getList($where);

        if (empty($info)) {
            throw new BusinessException('sys_is_empty');
        }

        return $info;
    }

    # 删除
    public function adminRemove($param): bool
    {
        $id = $param['id'];

        $where = [];
        $where[] = ['id', '=', $id];
        $where[] = ['del_status', '=', 0];

        $Admin = new Admin();
        $res = $Admin->getInfo($where);
        if (empty($res)) {
            throw new BusinessException('sys_is_empty');
        }

        $data = [];
        $data['deleted_at'] = date('Y-m-d H:i:s', time());
        $data['del_status'] = 1;

        return $Admin->upData($where, $data);
    }

    /**
     * 生成随机字符串
     * @param string $length 长度
     * @return string 字符串
     */
    public function create_randomstr($length = 6)
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }

}
