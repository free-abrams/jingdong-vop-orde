<?php

namespace app\admin\validate;

use app\common\model\mysql\Admin\Admin;
use think\facade\Request;
use think\Validate;

class AdminValidate extends Validate
{

    protected $rule =   [
        # 分页
        'page'               => 'require|number|min:1',
        'page_size'          => 'require|number|between:10,100',

        # 搜索
        'search_name'        => 'chsAlphaNum', # 验证某个字段的值只能是汉字、字母和数字

        # 数据
        'id'                 => 'require|number',
        'username'           => 'require',
        'password'           => 'require|length:6,25',
        'state'              => 'require',

    ];

    protected $message  =   [
        'username.require' => '用户名必须',
        'username.chsAlphaNum' => '用户名只能是字母',
        'password.require' => '密码必须',
        'password.length' => '密码必须大于6位',
        'password_confirm.confirm' => '两次密码不一致',
        'state.require' => '状态必须',
    ];

    protected $scene = [
        'list' => ['page','page_size','search_name'],
        'save' => ['id','username','login_ip','status'],
        'info' => ['id'],
        'remove' => ['id'],
        'menu' => ['null'],
    ];

    public function sceneSave(): AdminValidate
    {
        $id = Request::param('id');
        if ($id > 0) {
            return $this->only($this->scene['save'])
            ->append('title', 'require|unique:'.Admin::class.',title,' . $id)
            ->append('password', 'require')
            ->append('password_confirm', 'require|confirm');
        } else {
            return $this->only($this->scene['save'])
            ->append('title', 'require|unique:'.Admin::class.',title');
        }
    }
}
