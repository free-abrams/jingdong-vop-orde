<?php

namespace app\admin\validate;

use think\Validate;

class AuthGroupValidate extends Validate
{

    protected $rule =   [
        # 分页
        'page'               => 'require|number|min:1',
        'page_size'          => 'require|number|between:10,100',

        # 搜索
        'search_title'       => 'chsAlphaNum', # 验证某个字段的值只能是汉字、字母和数字

        # 数据
        'id'                 => 'require|number',
        'title'              => 'require',
        'alias'              => 'require',
        'rules'              => 'require',

    ];

    protected $message  =   [
        'title.require' => '组名必须',
        'alias.require' => '别名必须',
        'rules.require' => '规则必须',
    ];

    protected $scene = [
        'list' => ['page','page_size','search_title'],
        'save' => ['id','title','alias','rem','rules'],
        'info' => ['id'],
        'remove' => ['id'],
    ];
}
