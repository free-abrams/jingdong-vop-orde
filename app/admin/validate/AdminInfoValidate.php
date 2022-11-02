<?php

namespace app\admin\validate;

use think\Validate;

class AdminInfoValidate extends Validate
{
    protected $rule =   [
        'username'           => 'require|mobile|min:11|max:11',
        'nickname'           => 'require',
        'status'             => 'number',
        'avatar'             => 'url',
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
        'info' => ['null'],
        'resetPassword' => ['password', 'password_confirm'],
        'save' => ['nickname', 'avatar', 'status','password', 'password_confirm'],
    ];
}