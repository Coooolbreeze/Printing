<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/7
 * Time: 16:51
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderPayTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Events\OrderPaid;
use App\Exceptions\BaseException;
use App\Models\BalanceRecord;
use App\Models\Order;
use App\Models\RechargeOrder;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;
use Yansongda\LaravelPay\Facades\Pay;

class AliPayController extends ApiController
{
    /**
     * @param $orderId
     * @return mixed
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public function pay($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!TokenFactory::isValidOperate($order->user_id)) {
            throw new BaseException('不能支付他人的订单');
        }
        if ($order->status == OrderStatusEnum::EXPIRE) {
            throw new BaseException('该订单已过期，请重新下单');
        }
        if ($order->status >= OrderStatusEnum::PAID) {
            throw new BaseException('该订单已支付，请不要重复支付');
        }

        $order = [
            'out_trade_no' => $order->order_no,
            'total_amount' => $order->total_price,
            'subject' => '易特印-商品订单支付'
        ];

        self::setNotifyUrl('/api/alipay/notify');
        self::setReturnUrl(config('app.url') . '/alipay.html');

        return Pay::alipay()->web($order);
    }

    public function recharge(Request $request)
    {
        $balanceOrder = RechargeOrder::generate($request->price);

        $order = [
            'out_trade_no' => $balanceOrder->order_no,
            'total_amount' => $balanceOrder->price,
            'subject' => '易特印-余额充值'
        ];

        self::setNotifyUrl('/api/alipay/recharge_notify');
        self::setReturnUrl(config('app.url') . '/alipay.html');

        return Pay::alipay()->web($order);
    }

    public function return()
    {
        $data = Pay::alipay()->verify();

        // 订单号：$data->out_trade_no
        // 支付宝交易号：$data->trade_no
        // 订单总金额：$data->total_amount
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function notify()
    {
        $aliPay = Pay::alipay();

        \DB::transaction(function () use ($aliPay) {
            $data = $aliPay->verify();

            if ($data->trade_status == 'TRADE_SUCCESS') {
                $order = Order::where('order_no', $data->out_trade_no)
                    ->lockForUpdate()
                    ->first();

                if ($order->status <= OrderStatusEnum::UNPAID) {
                    $order->update([
                        'status' => OrderStatusEnum::PAID,
                        'pay_type' => OrderPayTypeEnum::ALI_PAY
                    ]);
                    event(new OrderPaid($order));
                }
            }

            \Log::debug('Alipay notify', $data->all());
        });

        return $aliPay->success();
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function rechargeNotify()
    {
        $aliPay = Pay::alipay();

        \DB::transaction(function () use ($aliPay) {
            $data = $aliPay->verify();

            if ($data->trade_status == 'TRADE_SUCCESS') {
                $order = RechargeOrder::withoutGlobalScope('is_paid')
                    ->where('order_no', $data->out_trade_no)
                    ->lockForUpdate()
                    ->first();

                if ($order->is_paid == 0) {
                    $order->update([
                        'is_paid' => 1,
                        'pay_type' => OrderPayTypeEnum::ALI_PAY
                    ]);
                    BalanceRecord::income($order->price, '支付宝充值', $order->user);
                }
            }

            \Log::debug('Alipay notify', $data->all());
        });

        return $aliPay->success();
    }

    public static function setNotifyUrl($notify)
    {
        config([
            'pay.alipay.notify_url' => config('app.url') . $notify
        ]);
    }

    public static function setReturnUrl($return)
    {
        config([
            'pay.alipay.return_url' => $return
        ]);
    }
}