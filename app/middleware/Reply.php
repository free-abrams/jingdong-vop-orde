<?php
declare (strict_types = 1);

namespace app\middleware;

use app\common\exception\BusinessException;
use think\facade\Request;

class Reply
{
    /**
     * 处理请求
     *
     * @param $request
     * @param \Closure $next
     * @return mixed|void
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
//        $getData = $response->getData();
//        if(isset($getData['code']) && $getData['code'] !== 200){
//            return $response;
//        }
//
//        $name = app('http')->getName();
//        $namespace = 'app\\' .$name . '\validate';
//        $class = Request::controller();
//
//        $className = $namespace .'\\'. class_basename($class).'Validate';
//        $validate = validate($className);
//        if(!$validate->hasScene(Request::action())){
//            throw new BusinessException('sys_err_validate_is_empty');
//        }
//
//        $validate->scene(Request::action())->check(Request::param());

        return $response;
    }

//    public function end(\think\Response $response){
//        dump('控制器之后');
//    }
}

