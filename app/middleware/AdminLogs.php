<?php

namespace app\middleware;

use app\common\business\Admin\AdminLogsBusiness;

class AdminLogs
{
    private $ignore = [
        'list',
    ];

    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        if (in_array($request->action(), $this->ignore)) {
            return $response;
        }
        $add = [
            'admin_id' => $request->uid,
            'route' => $request->controller().'/'.$request->action(),
            'param' => $request->param(),
        ];
        (new AdminLogsBusiness())->save($add);

        return $response;
    }
}