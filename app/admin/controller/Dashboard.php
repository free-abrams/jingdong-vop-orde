<?php

namespace app\admin\controller;

use app\BaseController;
use app\common\business\Statistics\StatisticsBusiness;
use app\common\lib\ErrorCode;
use app\middleware\JWT;
use think\response\Json;

class Dashboard extends BaseController
{
    protected $middleware = [JWT::class];

    public function overall(): Json
    {
        $resp = (new StatisticsBusiness())->overall();

        return show(ErrorCode::$errors['success'], $resp);
    }

    public function saleAmountTrend(): Json
    {
        $resp = (new StatisticsBusiness())->saleAmountTrend(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    public function saleNumTrend(): Json
    {
        $resp = (new StatisticsBusiness())->saleNumTrend(self::$param);

        return show(ErrorCode::$errors['success'], $resp);
    }

    public function saleRankings(): Json
    {
        $resp = (new StatisticsBusiness())->saleRankings();

        return show(ErrorCode::$errors['success'], $resp);
    }

    public function tradeAmountRank(): Json
    {
        $resp = (new StatisticsBusiness())->tradeAmountRank();

        return show(ErrorCode::$errors['success'], $resp);
    }
}