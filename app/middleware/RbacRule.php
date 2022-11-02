<?php

namespace app\middleware;


class RbacRule
{
    # 需要鉴定权限的操作
    const action = [
        'firstAudit',
        'secondAudit'
    ];
    /**
     * RBAC
     * @param $request
     * @param \Closure $next
     * @return mixed|void
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

//        if (in_array($request->action(), self::action)) {
//            $rule = $request->action();
//        } else {
//            $rule = $request->controller();
//        }
//
//        $admin_rule = (new Admin())->field('id')->find($request->uid);
//        $rules = $admin_rule->getRules();
//
//        if (!in_array($rule, $rules)) {
//            throw new BusinessException('rbac_err');
//        }

        return $response;
    }
}
