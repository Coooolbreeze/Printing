<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\OrderExpire;
use App\Models\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCreatedListener
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
     * @param  OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        OrderExpire::dispatch($order)
            ->delay(now()->addMinute(30));
    }
}
