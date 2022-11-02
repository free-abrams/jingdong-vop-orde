<?php
namespace app\common\model\Traits;

use app\common\exception\BusinessException;
use app\common\model\mysql\User\UserBalanceQueue;

trait Commission {

    protected $commissionRule = [
        [
            'id' => 1,
            'name' => '陈氏太乙名医堂传播大使',
            '1st' => 40,
            '2nd' => 5,
            '3rd' => 10,
        ],[
            'id' => 2,
            'name' => '陈氏太乙名医堂传承人',
            '1st' => 50,
            '2nd' => 5,
            '3rd' => 10,
        ]
    ];

    public function handel(array $param, array $commissionRule, &$resContent)
    {
        $data = $param;
        $data['id'] = 1;
        $data['lever'] = 1;
        $data['price'] = 19800;
        $data['user_id'] = 1;
        $commissionHash = array_column($commissionRule, null, 'id');
        if (isset($commissionHash[$data['id']])) {
            $savePrice = $data['price'] * $commissionHash[$data['id']][$data['lever']] / 100;
            $resContent[] = [
                'user_id' => $data['user_id'],
                'source_user_id' => $param['source_user_id'],
                'balance' => $savePrice,
                'type' => 1,
                'status' => 2,
            ];
        }
    }

    public function push(array $readyQueue): bool
    {
        $model = (new UserBalanceQueue());
        $res = $model->saveAll($readyQueue);
        if (empty($res)) {
            throw new BusinessException('add_err');
        }
        return true;
    }
}