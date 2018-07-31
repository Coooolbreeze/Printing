<?php

namespace App\Listeners;

use App\Enum\OrderStatusEnum;
use App\Events\OrderUpdated;
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
     * Handle the event.
     *
     * @param  OrderUpdated  $event
     * @return void
     */
    public function handle(OrderUpdated $event)
    {
        $order = $event->order;

        // 支付
        if ($order->status == OrderStatusEnum::PAID && !$order->paid_at) {
            $order->update(['paid_at' => Carbon::now()]);
        }
        // 审核通过
        elseif ($order->status == OrderStatusEnum::UNDELIVERED && !$order->audited_at) {
            OrderLog::write($order->id, '审核通过');
            $order->update(['audited_at' => Carbon::now()]);
        }
        // 发货
        elseif ($order->status == OrderStatusEnum::DELIVERED && !$order->delivered_at) {
            OrderLog::write($order->id, '发货');
            $order->update(['delivered_at' => Carbon::now()]);
        }
        // 收货
        elseif ($order->status == OrderStatusEnum::RECEIVED && !$order->received_at) {
            $order->update(['received_at' => Carbon::now()]);
        }
    }
}
