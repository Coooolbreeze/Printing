<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/8
 * Time: 17:02
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderPayTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Events\OrderPaid;
use App\Exceptions\BaseException;
use App\Models\BalanceRecord;
use App\Models\Order;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class BalancePayController extends ApiController
{
    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function pay(Request $request)
    {
        \DB::transaction(function () use ($request) {
            $order = Order::lockForUpdate()->findOrFail($request->order_id);

            if (!TokenFactory::isValidOperate($order->user_id)) {
                throw new BaseException('不能支付他人的订单');
            }
            if ($order->status == OrderStatusEnum::EXPIRE) {
                throw new BaseException('该订单已过期，请重新下单');
            }
            if ($order->status >= OrderStatusEnum::PAID) {
                throw new BaseException('该订单已支付，请不要重复支付');
            }

            BalanceRecord::expend($order->total_price, [
                'describe' => '订单消费',
                'order_no' => $order->order_no
            ]);

            $order->update([
                'status' => OrderStatusEnum::PAID,
                'pay_type' => OrderPayTypeEnum::BALANCE
            ]);
            event(new OrderPaid($order));
        });

        return $this->message('支付成功');
    }
}