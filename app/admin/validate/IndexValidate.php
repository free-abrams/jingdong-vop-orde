<?php

namespace app\api\validate;

use think\Validate;

class IndexValidate extends Validate
{

    # 课程评价 - 列表（分页）
    public function sceneList(): IndexValidate
    {
        return $this->only(['page','size'])
            ->append('page', 'require|number')
            ->append('size', 'require|number')
            ;
    }
}
