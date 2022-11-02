<?php

namespace app\common\business\System;

use app\common\business\User\UserBalanceBusiness;
use app\common\exception\BusinessException;
use app\common\model\mysql\Order\Order;
use app\common\model\mysql\System\SystemWxBills;
use app\common\model\mysql\System\SystemConfig;
use app\common\model\mysql\System\SystemWxRefund;
use WeChat\Pay;
use WePay\Refund;

class SystemBillBusiness
{
    // 取消订单然后退款
    public function cancelOrder($param): bool
    {
        $where = [];
        $where[] = ['order_id', '=', $param['id']];
        $where[] = ['status', '=', 1];
        $bill = (new SystemWxBills());
        $map = $bill->getList($where);

        foreach ($map as $v) {
            switch ($v['type']) {
                case 1:
                    $res = $this->refundWxPay($param, $v);
                    break;
                case 2:
                    $res = $this->refundBalance($param, $v);
                    break;
                default:
                    $res = true;
            }
        }

        return true;
    }

    public function refundBalance($param, $bill): bool
    {
        $map['id'] = $param['id'];
        $map['user_id'] = $param['user_id'];
        $map['table_type'] = Order::class;
        $map['source_user_id'] = 0;
        $map['desc'] = '订单号：'.$param['no'];
        $map['balance'] = $bill['amount'];

        return (new UserBalanceBusiness())->handel('order_refund_inc', $map);
    }

    public function refundWxPay($order, $bill): array
    {
        $add = [];
        $add['user_id'] = $order['user_id'];
        $add['no'] = get_number_chang(16, 'TK');
        $add['title'] = '退款';
        $add['status'] = 1;
        $add['total_fee'] = $bill['amount'];
        $add['refund_fee'] = $bill['amount'];

        $wxRefundOrder = (new SystemWxRefund())->add($add);
        if (!$wxRefundOrder) {
            throw new BusinessException('add_err');
        }
        $where = [];
        $where[] = ['key', 'in', ['wechatPay', 'mini']];
        $configs = (new SystemConfig())->getList($where);
        $config = array_merge($configs[0]['value'], $configs[1]['value']);
        $wechat = Pay::instance($config);

        $options = [
            'transaction_id' => $bill['transaction_id'],
            'out_refund_no'  => $add['no'],
            'total_fee'      => $add['total_fee'],
            'refund_fee'     => $add['refund_fee'],
        ];

        return $wechat->createRefund($options);
    }
}