<?php

namespace app\common\business\Config;

use app\common\business\BaseBusiness;
use app\common\exception\BusinessException;
use app\common\model\mysql\System\SystemConfig;
use think\facade\Cache;

class SystemConfigBusiness extends BaseBusiness
{
    const key = [
        'qiniu', // 七牛云
        'alibabaCaptcha',// 阿里云短信
        'mini',// 小程序
        'wechatPay',// 微信支付
        'wechatPublic',// 微信公众号
    ];
    # 列表 - 分页&搜索
    public function SystemConfigList($param): array
    {
        $where   = [];

        # 用户端搜索
        $field = ['*'];

        $SystemConfig = new SystemConfig();
        return $SystemConfig->getList($where,'',$field);
    }

    # 新增&编辑
    public function SystemConfigSave($param): bool
    {
        $SystemConfig = new SystemConfig();

        $id = $param['id'];

        if(!$id){
            $resp = $SystemConfig->add($param);
        }
        else{
            $where = [];
            $where[] = ['id','=',$id];
            $resp = $SystemConfig->upData($where,$param);
        }

        return $resp;
    }

    # 详情
    public function SystemConfigInfo($param): array
    {
        $SystemConfig = new SystemConfig();

        $id = $param['id'];

        $where = [];
        $where['id'] =$id;

        $info = $SystemConfig->getInfo($where);

        if(empty($info)){
            throw new BusinessException('sys_is_empty');
        }

        return $info;
    }

    # 删除
    public function SystemConfigRemove($param): bool
    {
        $id = $param['id'];

        $where   = [];
        $where[] = ['id', '=', $id];
        $where[] = ['del_status', '=', 0];

        $SystemConfig = new SystemConfig();
        $res     = $SystemConfig->getInfo($where);
        if (empty($res)) {
            throw new BusinessException('sys_is_empty');
        }

        $data               = [];
        $data['deleted_at'] = date('Y-m-d H:i:s', time());
        $data['del_status']  = 1;

        return $SystemConfig->upData($where, $data);
    }

    public function getConfigs($param): array
    {
        $where = [];
        return (new SystemConfig())->getList($where);
    }

    public function save($param): bool
    {
        $where[] = ['key', '=', $param['key']];
        $model = (new SystemConfig())->where($where)->find();
        if (!$model->save($param)) {
            throw new BusinessException('edit_err');
        }
        return true;
    }

    public function saveAll($param): bool
    {
        $model = (new SystemConfig());
        if (!$model->saveAll($param)) {
            throw new BusinessException('edit_err');
        }
        return true;
    }

    public function clearCache(): bool
    {
        return Cache::clear();
    }
}
