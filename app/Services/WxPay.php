<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/15
 * Time: 16:35
 */

namespace App\Services;


use App\Enum\OrderPayTypeEnum;
use App\Models\RechargeOrder;
use Yansongda\LaravelPay\Facades\Pay as LaravelPay;

class WxPay extends Pay
{
    public function pay()
    {
        parent::pay();

        $order = [
            'out_trade_no' => $this->getOrder()->order_no,
            'total_fee' => $this->getTotalPrice() * 100,
            'body' => '易特印-商品订单支付'
        ];

        return LaravelPay::wechat()->scan($order);
    }

    public function recharge($price)
    {
        $balanceOrder = RechargeOrder::generate($price);

        $order = [
            'out_trade_no' => $balanceOrder->order_no,
            'total_fee' => $balanceOrder->price * 100,
            'body' => '易特印-余额充值'
        ];

        return LaravelPay::wechat()->scan($order);
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function notify()
    {
        $wxPay = LaravelPay::wechat();

        \DB::transaction(function () use ($wxPay) {
            $data = $wxPay->verify();

            if ($data->result_code == 'SUCCESS') {
                $this->setPayType(OrderPayTypeEnum::WX_PAY)->successful($data->out_trade_no);
            }

            \Log::debug('Wxpay notify', $data->all());
        });

        return $wxPay->success();
    }
}