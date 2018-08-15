<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/15
 * Time: 11:04
 */

namespace App\Services;


use App\Enum\OrderStatusEnum;
use App\Events\OrderPaid;
use App\Exceptions\BaseException;
use App\Models\BalanceRecord;
use App\Models\Order;
use App\Models\RechargeOrder;
use App\Services\Tokens\TokenFactory;

class Pay
{
    private $payType = null;
    private $orderNo = null;
    private $order = null;
    private $totalPrice = null;

    public function setPayType($payType)
    {
        $this->payType = $payType;
        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public function pay()
    {
        $this->validateOrder();

        $totalPrice = $this->order->total_price;

        $totalPrice -= $this->balanceDeducted();

        $this->totalPrice = $totalPrice;
    }

    /**
     * @return array|\Illuminate\Http\Request|int|string
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    protected function balanceDeducted()
    {
        if (request('balance')) {
            if (TokenFactory::getCurrentUser()->balance < request('balance'))
                throw new BaseException('可用余额不足');

            $this->order->update([
                'balance_deducted' => request('balance')
            ]);

            return request('balance');
        } else {
            $this->order->update([
                'balance_deducted' => 0
            ]);

            return 0;
        }
    }

    /**
     * @param $orderNo
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    protected function successful($orderNo)
    {
        $this->orderNo = $orderNo;

        $preCode = substr($orderNo, 0, 1);

        if ($preCode == 'E') {
            $this->entityOrderNotify();
        }
        elseif ($preCode == 'R') {
            $this->rechargeOrderNotify();
        }
    }

    /**
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    protected function entityOrderNotify()
    {
        $order = Order::where('order_no', $this->orderNo)
            ->lockForUpdate()
            ->first();

        if ($order->status <= OrderStatusEnum::UNPAID) {
            if ($order->balance_deducted > 0) {
                BalanceRecord::expend($order->balance_deducted, '订单抵扣', $order->user);
            }

            $order->update([
                'status' => OrderStatusEnum::PAID,
                'pay_type' => $this->payType
            ]);

            event(new OrderPaid($order));
        }
    }

    /**
     * @throws \Throwable
     */
    protected function rechargeOrderNotify()
    {
        $order = RechargeOrder::withoutGlobalScope('is_paid')
            ->where('order_no', $this->orderNo)
            ->lockForUpdate()
            ->first();

        if ($order->is_paid == 0) {
            $order->update([
                'is_paid' => 1,
                'pay_type' => $this->payType
            ]);

            BalanceRecord::income($order->price, '支付宝充值', $order->user);
        }
    }

    /**
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    protected function validateOrder()
    {
        if (!TokenFactory::isValidOperate($this->order->user_id)) {
            throw new BaseException('不能支付他人的订单');
        }
        if ($this->order->status == OrderStatusEnum::EXPIRE) {
            throw new BaseException('该订单已过期，请重新下单');
        }
        if ($this->order->status >= OrderStatusEnum::PAID) {
            throw new BaseException('该订单已支付，请不要重复支付');
        }
    }
}