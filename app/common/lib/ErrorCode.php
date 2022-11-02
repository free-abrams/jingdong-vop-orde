<?php

namespace app\common\lib;

class ErrorCode
{
    // 预定义错误信息
    public static $errors
        = [

            'success'      => ['code' => 200, 'message' => '操作成功！'],

            # 框架错误
            'sys_err_http' => ['code' => 400, 'message' => '请求方式错误'],

            'sys_err_app'       => ['code' => 404, 'message' => '请求应用错误！'],
            'sys_err_module'    => ['code' => 404, 'message' => '请求模块错误！'],
            'sys_err_fun_res'   => ['code' => 404, 'message' => '请求方法错误！'],
            'sys_err_route_res' => ['code' => 404, 'message' => '请求地址无效！'],

            'sys_err_token'             => ['code' => 401, 'message' => 'token认证错误'],
            'sys_err_param'             => ['code' => 402, 'message' => '请求参数错误'],
            'sys_err_validate'          => ['code' => 403, 'message' => '数据验证失败'],
            'sys_err_validate_is_empty' => ['code' => 403, 'message' => '数据验证不存在'],

            'sys_err_type'                     => ['code' => 405, 'message' => '类型错误'],
            'sys_err_db'                       => ['code' => 406, 'message' => '数据库操作失败'],
            'sys_err'                          => ['code' => 407, 'message' => '系统出错，已通知开发人员！'],
            'sys_err_wx'                       => ['code' => 409, 'message' => '微信错误！'],
            'sys_is_empty'                     => ['code' => 408, 'message' => '数据不存在！'],

            # 数据请求
            'throttle_error'                   => ['code' => 501, 'message' => '超出访问频率限制，请稍后重试！'],

            # 数据操作
            'add_err'                          => ['code' => 1001, 'message' => '添加失败，请重试！'],
            'del_err'                          => ['code' => 1002, 'message' => '删除失败，请重试！'],
            'edit_err'                         => ['code' => 1003, 'message' => '编辑失败，请重试！'],
            'get_err'                          => ['code' => 1004, 'message' => '查询失败，请重试！'],
            'repeat_err'                       => ['code' => 1005, 'message' => '重复数据添加，请检查！'],

            # 用户
            'user_is_exists'                   => ['code' => 1001, 'message' => '账号已存在！'],
            'user_is_empty'                    => ['code' => 1001, 'message' => '账号不存在！'],
            'user_password_err'                => ['code' => 1001, 'message' => '密码错误！'],
            'user_unregistered_err'            => ['code' => 200, 'message' => '首次微信登录，请绑定手机号！'],
            'get_user_wechat_phone_err'        => ['code' => 1001, 'message' => '获取手机号失败！'],

            #登录
            'login_successful'                 => ['code' => 1001, 'message' => '登录成功！'],
            'login_failed'                     => ['code' => 1001, 'message' => '登录失败！'],

            # 权限
            'rbac_menu_err'                    => ['code' => 4403, 'message' => '用户权限错误，请检查！'],
            'rbac_err'                         => ['code' => 4403, 'message' => '没有执行此操作的权限'],

            # 商品分类
            'category_has_products_in_use_err' => ['code' => 5001, 'message' => '此商品分类有商品正在使用！'],

            # 短信
            'code_err'                         => ['code' => 9001, 'message' => '短信验证码错误！'],
            'code_is_send_err'                 => ['code' => 9002, 'message' => '短信验证码已发送！'],
            'code_send_err'                    => ['code' => 9003, 'message' => '短信验证码发送失败！'],
            'code_is_use_err'                  => ['code' => 9004, 'message' => '短信验证码已被使用！'],
        ];

}
