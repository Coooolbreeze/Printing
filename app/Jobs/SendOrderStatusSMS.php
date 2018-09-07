<?php

namespace App\Jobs;

use App\Enum\OrderStatusEnum;
use App\Models\Order;
use App\Services\SMS;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOrderStatusSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, $status)
    {
        $this->order = $order;

        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = $this->order;

        if ($this->status == 'audited') {
            SMS::orderAudited($order->user->phone, $order->user->nickname, $order->order_no);
        }
        if ($this->status == 'delivered') {
            SMS::orderDelivered($order->user->phone, $order->user->nickname, $order->order_no);
        }
    }
}
