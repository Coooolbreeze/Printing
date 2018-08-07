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
use App\Models\Order;
use App\Services\Tokens\TokenFactory;
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
            'subject' => '易特印',
        ];

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

            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况
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
}