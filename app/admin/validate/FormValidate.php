<?php

namespace app\admin\validate;

use app\common\model\mysql\System\SystemForm;
use think\facade\Request;
use think\Validate;

class FormValidate extends Validate
{
    protected $rule =   [
        # 分页
        'page'               => 'require|number|min:1',
        'page_size'          => 'require|number|between:10,100',

        # 搜索
        'search_name'        => 'chsAlphaNum', # 验证某个字段的值只能是汉字、字母和数字

        # 数据
        'id'                 => 'require|number',
        'name'               => 'require',
        'type'               => 'require|number',
        'url'                => 'require',

    ];

    protected $message  =   [
        'name.require' => '名称必须',
        'type.require' => '类型必须',
        'type.number' => '类型错误',
        'url.require' => 'url地址必须',
    ];

    protected $scene = [
        'list' => ['page','page_size'],
        'save' => ['id','name','items', 'button_text', 'confirm_tips'],
        'info' => ['id'],
        'remove' => ['id'],
        'submitUserList' => ['id','page','page_size'],
    ];

    public function sceneSave(): FormValidate
    {
        $id = Request::param('id');
        if ($id > 0) {
            return $this->only(['id','name','image_path','sort'])
                ->append('name', 'require|unique:'.SystemForm::class.',name,'.$id);
        } else {
            return $this->only(['id','name','image_path','sort'])
                ->append('name', 'require|unique:'.SystemForm::class.',name');
        }
    }
}