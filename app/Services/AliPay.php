<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/15
 * Time: 11:10
 */

namespace App\Services;


use App\Enum\OrderPayTypeEnum;
use App\Models\BalanceRecord;
use App\Models\Order;
use App\Models\RechargeOrder;
use Yansongda\LaravelPay\Facades\Pay as LaravelPay;

class AliPay extends Pay
{
    public function pay()
    {
        parent::pay();

        $order = [
            'out_trade_no' => $this->getOrder()->order_no,
            'total_amount' => $this->getTotalPrice(),
            'subject' => '易特印-商品订单支付'
        ];

        return LaravelPay::alipay()->web($order);
    }

    public function recharge($price)
    {
        $balanceOrder = RechargeOrder::generate($price);

        $order = [
            'out_trade_no' => $balanceOrder->order_no,
            'total_amount' => $balanceOrder->price,
            'subject' => '易特印-余额充值'
        ];

        return LaravelPay::alipay()->web($order);
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function notify()
    {
        $aliPay = LaravelPay::alipay();

        \DB::transaction(function () use ($aliPay) {
            $data = $aliPay->verify();

            if ($data->trade_status == 'TRADE_SUCCESS') {
                $this->setPayType(OrderPayTypeEnum::ALI_PAY)->successful($data->out_trade_no, $data->trade_no);
            }

            \Log::debug('Alipay notify', $data->all());
        });

        return $aliPay->success();
    }

    public function refund()
    {
        parent::refund();

        return LaravelPay::alipay()->refund([
            'out_trade_no' => $this->getOrder()->order_no,
            'refund_amount' => $this->getTotalPrice()
        ]);
    }
}