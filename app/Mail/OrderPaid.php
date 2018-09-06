<?php

namespace App\Mail;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaid extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(config('setting.payment_notify_email'))
            ->subject('订单' . $this->order->order_no . '付款通知')
            ->html(
                '订单' . $this->order->order_no . '已付款，付款金额' .
                $this->order->total_price . '元，付款方式为' .
                OrderResource::convertPayType($this->order->pay_type)
            );
    }
}
