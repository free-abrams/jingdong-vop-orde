<?php

namespace app\admin\validate;

use think\Validate;

class LoginValidate extends Validate
{

    protected $rule =   [
        'username'           => 'require|mobile|min:11|max:11',
        'password'           => 'require',
        'password_confirm'   => 'require|confirm:password'
    ];

    protected $message  =   [
        'username.require' => '用户名必须',
        'username.mobile' => '用户名必须是手机号码',
        'password.require' => '密码必须',
        'password_confirm.confirm' => '两次密码不一致',
    ];

    protected $scene = [
        'sign_in' => ['username', 'password'],
        'get_member_info' => ['null'],
        'resetPassword' => ['password', 'password_confirm']
    ];

}
