<?php

namespace app\common\model\Traits;

use app\common\lib\Tree;
use app\common\lib\TreeUtil;
use think\facade\Cache;

trait RbacCheck
{
    // 缓存相关配置
    protected $cache_key = '_cache_rules';

    protected $menu_cache = '_menu_cache'; //菜单缓存key

    /**
     * 获取当前用户的所有权限
     * @return mixed
     */
    public function getRules()
    {
        $cache_key = $this->id . $this->cache_key;
        if (!Cache::get($cache_key)) {
            $rules = [];
            foreach ($this->roles as $v) {
                if (isset($v['rules'])) {
                    $temp  = $v->toArray();
                    $rules = array_merge($rules, $temp['rules']);
                }
            }
            if (empty($rules)) {
                return [];
            }
            $rules = array_column($rules, 'rule', 'id');
            $rules = array_flip($rules);
            unset($rules['']);
            /**将权限路由存入缓存中*/
            Cache::tag('menu')->set($cache_key, array_keys($rules), 86400);
        }

        return Cache::get($cache_key);
    }

    /**
     * 获取树形菜单导航栏
     * @return array
     */
    public function getMenus(): array
    {
        $menu_cache = $this->id . $this->menu_cache;
        if (!Cache::get($menu_cache)) {

            # 开始组装数组
            $menu = $this->roles->toArray();

            foreach ($menu as $v) {
                if (isset($v['rules'])) {
                    $menu = array_merge($menu, $v['rules']);
                }
            }

            $menu = array_column($menu, null, 'id');
            asort($menu);

            foreach ($menu as $k => $v) {
                if ($v['is_menu'] !== 1) {
                    unset($menu[$k]);
                }
            }

            if (empty($menu)) {
                return [];
            }
            # 将权限路由存入缓存中
            Cache::tag('menu')->set($menu_cache, array_values($menu), 86400);
        }

        $menu    = Cache::get($menu_cache);
        $arr     = TreeUtil::listToTreeMulti($menu, 0, 'id', 'p_id', 'children');

        $routers = [];
        foreach ($arr as $v) {
            $temp = $this->getMenuData($v);
            foreach ($v['children'] as $vo) {
                $temp2 = $this->getMenuData($vo);
                foreach ($vo['children'] as $vv) {
                    $temp2['children'][] = $this->getMenuData($vv);
                }
                $temp['children'][] = $temp2;
            }
            $routers[] = $temp;
        }

        return $routers;
    }

    protected function getMenuData($data): array
    {
        $temp              = [];
        $temp['name']      = $data['name'];
        $temp['path']      = $data['path'];

        # 重定向
        if ($data['redirect']) {
            $temp['redirect'] = $data['redirect'];
        }
        $temp['component'] = $data['component'];
        # 如果设置为true，则项目将不会显示在侧边栏中（默认为false）
        if ($data['hidden']) {
            $temp['hidden'] = (boolean)$data['hidden'];
        }
        # 如果设置为true，将始终显示根菜单
        if ($data['always_show']) {
            $temp['alwaysShow'] = (boolean)$data['always_show'];
        }

        # meta 参数
        if($data['rule']){
            $temp['meta']['roles'] = [$data['rule']];
        }
        $temp['meta']['title'] = $data['title'];
        $temp['meta']['icon']  = $data['icon'];
        if ($data['no_cache']) {
            $temp['meta']['noCache'] = (boolean)$data['no_cache'];
        }
        if($data['active_menu']){
            $temp['meta']['activeMenu'] = $data['active_menu'];
        }

        return $temp;
    }

    /**
     * 删除权限缓存和菜单缓存
     * @return bool
     */
    public static function clearRuleAndMenu(): bool
    {
        return Cache::tag(['rbac', 'menu'])->clear();
    }
}
