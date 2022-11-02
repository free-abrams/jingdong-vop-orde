<?php

namespace app\admin\validate;

use think\Validate;

class AuthRuleValidate extends Validate
{

    protected $rule =   [
        # 分页
        'page'               => 'require|number|min:1',
        'page_size'          => 'require|number|between:10,100',

        # 搜索
        'search_title'       => 'chsAlphaNum', # 验证某个字段的值只能是汉字、字母和数字

        # 数据
        'id'                 => 'require',
        'name'               => 'require|alpha',
        'title'              => 'require|chsAlphaNum',
        'type'               => 'require|number',
        'status'             => 'require|in:0,1',
        'category'           => 'require|alpha',
        'class'              => 'require|number',
        'pid'                => 'require|number',
        'menu_title'         => 'require|chs',
        'sort'               => 'require|number',

    ];

    protected $message  =   [
        'name.require' => '规则唯一标识必须',
        'name.alpha' => '规则唯一标识必须为字母',
        'title.require' => '名称必须',
        'title.chsAlphaNum' => '只能是汉字、字母和数字',
        'type.require' => '状态必须',
        'type.number' => '状态必须为数字',
        'status.require' => '规则状态必须',
        'status.in' => '规则状态只能是0或者1',
        'category.require' => '权限类别必须',
        'category.alpha' => '权限类别必须为字母',
        'class.require' => '权限分组必须',
        'class.number' => '权限分组必须为数字',
        'pid.require' => '父级id必须',
        'pid.number' => '父级id必须为数字',
        'menu_title.require' => '中文名称必须',
        'menu_title.chs' => '中文名称必须为汉字',
        'sort.require' => '顺序必须',
        'sort.number' => '顺序必须为数字',
    ];

    protected $scene = [
        'list' => ['page','page_size','search_title'],
        'save' => ['id','name','title','type','status','condition','category','class','pid','menu_title','icon','sort'],
        'info' => ['id'],
        'remove' => ['id'],
    ];
}
