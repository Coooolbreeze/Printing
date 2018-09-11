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

        $res = LaravelPay::wechat()->scan($order);

        $this->getOrder()->update([
            'prepay_id' => $res->prepay_id
        ]);

        return [
            'code_url' => $res->code_url
        ];
    }

    public function recharge($price)
    {
        $rechargeOrder = RechargeOrder::generate($price);

        $order = [
            'out_trade_no' => $rechargeOrder->order_no,
            'total_fee' => $rechargeOrder->price * 100,
            'body' => '易特印-余额充值'
        ];

        $res = LaravelPay::wechat()->scan($order);

        $rechargeOrder->update([
            'prepay_id' => $res->prepay_id
        ]);

        return [
            'id' => $rechargeOrder->id,
            'code_url' => $res->code_url
        ];
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
                $this->setPayType(OrderPayTypeEnum::WX_PAY)->successful($data->out_trade_no, $data->transaction_id);
            }

            \Log::debug('Wxpay notify', $data->all());
        });

        return $wxPay->success();
    }

    public function refund()
    {
        parent::refund();

        return LaravelPay::wechat()->refund([
            'out_trade_no' => $this->getOrder()->order_no,
            'out_refund_no' => time(),
            'total_fee' => $this->getTotalPrice(),
            'refund_fee' => $this->getTotalPrice(),
            'refund_desc' => '订单退款'
        ]);
    }
}