<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/2
 * Time: 15:35
 */

namespace App\Listeners;


use App\Enum\OrderPayTypeEnum;
use App\Exceptions\BaseException;
use App\Jobs\SendOrderPaidEmail;
use App\Jobs\SendOrderStatusSMS;
use App\Mail\OrderPaid;
use App\Models\AccumulatePointsRecord;
use App\Models\Entity;
use App\Models\FinanceStatistic;
use App\Models\Message;
use App\Models\OrderLog;
use App\Models\TypeSale;
use App\Services\KDN;
use App\Services\SMS;
use Carbon\Carbon;

class OrderEventSubscriber
{
    private static function addSales($order)
    {
        $content = json_decode($order->snap_content, true);
        foreach ($content as $v) {
            Entity::where('id', $v['id'])
                ->increment('sales', self::getCount($v['count']));
        }
    }

    private static function getCount($count)
    {
        if (!is_numeric($count)) {
            preg_match_all('/\d+/', $count, $arr);
            $count = $arr[0][0];
        }
        return $count;
    }

    /**
     * 订单失效
     *
     * @param $event
     */
    public function onOrderExpire($event)
    {
        $order = $event->order;

        $order->user->coupons()->where('coupon_no', $order->coupon_no)
            ->update(['is_used' => 0]);
    }

    /**
     * 订单支付
     *
     * @param $event
     * @throws \Throwable
     */
    public function onOrderPaid($event)
    {
        $order = $event->order;

        $order->update(['paid_at' => Carbon::now()]);

        Message::orderPaid($order->user_id);

        self::addSales($order);

        $points = floor($order->total_price / config('setting.accumulate_points_money'));
        AccumulatePointsRecord::income($points, '购买商品', $order->user);

        $order->user()->increment('consume', $order->total_price);

        foreach (json_decode($order->snap_content) as $goods) {
            TypeSale::write($goods->type, $goods->price);
        }

        if ($order->pay_type == OrderPayTypeEnum::BACK_PAY) {
            OrderLog::write($order->id, '后台支付');
        } elseif (config('setting.payment_notify_email')) {

            if ($order->pay_type != OrderPayTypeEnum::BALANCE) {
                FinanceStatistic::write($order->order_no, $order->total_price);
            }

            SendOrderPaidEmail::dispatch($order);
        }
    }

    /**
     * 订单审核通过
     *
     * @param $event
     */
    public function onOrderAudited($event)
    {
        $order = $event->order;

        $order->update(['audited_at' => Carbon::now()]);

        OrderLog::write($order->id, '审核通过');

        Message::orderAudited($order->user_id);

        if (config('setting.sms_notify') && $order->user->phone) {
            SendOrderStatusSMS::dispatch($order, 'audited');
        }
    }

    /**
     * 订单审核未通过
     *
     * @param $event
     */
    public function onOrderFailed($event)
    {
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
    public function onOrderDelivered($event)
    {
        $order = $event->order;

        $order->update(['delivered_at' => Carbon::now()]);

        OrderLog::write($order->id, '发货');

        Message::orderDelivered($order->user_id);

        (new KDN($order->id))->generate();

        if (config('setting.sms_notify') && $order->user->phone) {
            SendOrderStatusSMS::dispatch($order, 'delivered');
        }
    }

    /**
     * 订单收货
     *
     * @param $event
     */
    public function onOrderReceived($event)
    {
        $order = $event->order;

        $order->update(['received_at' => Carbon::now()]);

        Message::orderReceived($order->user_id);
    }

    /**
     * 订单退款
     *
     * @param $event
     */
    public function onOrderRefunded($event)
    {
        $order = $event->order;

        $order->update(['refunded_at' => Carbon::now()]);

        OrderLog::write($order->id, '退款');

        FinanceStatistic::write($order->order_no, $order->total_price, 2);

        Message::orderRefunded($order->user_id);
    }

    /**
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\OrderExpire',
            'App\Listeners\OrderEventSubscriber@onOrderExpire'
        );

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

        $events->listen(
            'App\Events\OrderRefunded',
            'App\Listeners\OrderEventSubscriber@onOrderRefunded'
        );
    }
}