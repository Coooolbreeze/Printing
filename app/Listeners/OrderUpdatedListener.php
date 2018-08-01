<?php

namespace App\Listeners;

use App\Enum\OrderPayTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Events\OrderUpdated;
use App\Models\AccumulatePointsRecord;
use App\Models\OrderExpress;
use App\Models\OrderLog;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param OrderUpdated $event
     * @throws \Throwable
     */
    public function handle(OrderUpdated $event)
    {
        $order = $event->order;

        // 支付
        if ($order->status == OrderStatusEnum::PAID && !$order->paid_at) {
            $order->update(['paid_at' => Carbon::now()]);

            if ($order->pay_type == OrderPayTypeEnum::BACK_PAY)
                OrderLog::write($order->id, '后台支付');

            $points = floor($order->total_price / config('setting.accumulate_points_money'));
            AccumulatePointsRecord::income($points, '购买商品', $order->user);
        }
        // 审核通过
        elseif ($order->status == OrderStatusEnum::UNDELIVERED && !$order->audited_at) {
            $order->update(['audited_at' => Carbon::now()]);
            OrderLog::write($order->id, '审核通过');
        }
        // 发货
        elseif ($order->status == OrderStatusEnum::DELIVERED && !$order->delivered_at) {
            $order->update(['delivered_at' => Carbon::now()]);
            OrderLog::write($order->id, '发货');
        }
        // 收货
        elseif ($order->status == OrderStatusEnum::RECEIVED && !$order->received_at) {
            $order->update(['received_at' => Carbon::now()]);
        }
    }
}
