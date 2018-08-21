<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/2
 * Time: 15:35
 */

namespace App\Listeners;


use App\Enum\OrderPayTypeEnum;
use App\Models\AccumulatePointsRecord;
use App\Models\Message;
use App\Models\OrderLog;
use Carbon\Carbon;

class OrderEventSubscriber
{
    /**
     * 订单支付
     *
     * @param $event
     * @throws \Throwable
     */
    public function onOrderPaid($event) {
        $order = $event->order;

        $order->update(['paid_at' => Carbon::now()]);

        if ($order->pay_type == OrderPayTypeEnum::BACK_PAY) {
            OrderLog::write($order->id, '后台支付');
        }

        Message::orderPaid($order->user_id);

        $points = floor($order->total_price / config('setting.accumulate_points_money'));
        AccumulatePointsRecord::income($points, '购买商品', $order->user);

        $order->user()->increment('consume', $order->total_price);
    }

    /**
     * 订单审核通过
     *
     * @param $event
     */
    public function onOrderAudited($event) {
        $order = $event->order;

        $order->update(['audited_at' => Carbon::now()]);

        OrderLog::write($order->id, '审核通过');

        Message::orderAudited($order->user_id);
    }

    /**
     * 订单审核未通过
     *
     * @param $event
     */
    public function onOrderFailed($event) {
        $order = $event->order;

        $order->update(['audited_at' => Carbon::now()]);

        OrderLog::write($order->id, '审核未通过');

        Message::orderFailed($order->user_id);
    }

    /**
     * 订单发货
     *
     * @param $event
     */
    public function onOrderDelivered($event) {
        $order = $event->order;

        $order->update(['delivered_at' => Carbon::now()]);

        OrderLog::write($order->id, '发货');

        Message::orderDelivered($order->user_id);
    }

    /**
     * 订单收货
     *
     * @param $event
     */
    public function onOrderReceived($event) {
        $order = $event->order;

        $order->update(['received_at' => Carbon::now()]);

        Message::orderReceived($order->user_id);
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\OrderPaid',
            'App\Listeners\OrderEventSubscriber@onOrderPaid'
        );

        $events->listen(
            'App\Events\OrderAudited',
            'App\Listeners\OrderEventSubscriber@onOrderAudited'
        );

        $events->listen(
            'App\Events\OrderDelivered',
            'App\Listeners\OrderEventSubscriber@onOrderDelivered'
        );

        $events->listen(
            'App\Events\OrderReceived',
            'App\Listeners\OrderEventSubscriber@onOrderReceived'
        );

        $events->listen(
            'App\Events\OrderFailed',
            'App\Listeners\OrderEventSubscriber@onOrderFailed'
        );
    }
}