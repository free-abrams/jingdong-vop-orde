<?php

namespace app\admin\validate;

use think\Validate;

class DashboardValidate extends Validate
{
    protected $rule =   [
        # 分页
        'page'               => 'require|number|min:1',
        'page_size'          => 'require|number|between:10,100',
    ];

    protected $scene = [
        'overall' => ['nullable'],
        'saleNumTrend' => ['nullable'],
        'saleAmountTrend' => ['nullable'],
        'saleRankings' => ['nullable'],
        'tradeAmountRank' => ['nullable'],
    ];
}