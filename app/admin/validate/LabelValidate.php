<?php

namespace app\admin\validate;

use think\Validate;

class LabelValidate extends Validate
{
    protected $rule =   [
        'page'        => 'require|number',
        'page_size'   => 'require|number|between:10,100',
        'search_name' => 'chsAlphaNum', # # 验证某个字段的值只能是汉字、字母和数字

        'id'    => 'require|number|min:0',
        'member_lever_id' => 'number',
        'name'  => 'require|chsAlphaNum', # # 验证某个字段的值只能是汉字、字母和数字
        'remark' => 'require',
        'image_path' => 'require|url',
        'sort'  => 'require|number|min:0',
        'prices' => 'require|array',
    ];

    protected $message  =   [
        'name.require' => '名称必须',
    ];

    protected $scene = [
        'list' => ['page','page_size','search_name'],
        'save' => ['id','name','remark'],
        'info' => ['id'],
        'remove' => ['id'],
        'users' => ['id'],
    ];
}