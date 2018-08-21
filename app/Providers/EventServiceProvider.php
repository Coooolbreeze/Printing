<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\UserCreated' => [
            'App\Listeners\UserCreatedListener'
        ],
        'App\Events\UserAccumulatePointsIncome' => [
            'App\Listeners\UserAccumulatePointsIncomeListener'
        ],
        'App\Events\OrderPaid' => [
//            'App\Listeners\OrderEventSubscriber@onOrderPaid'
        ],
        'App\Events\OrderAudited' => [
//            'App\Listeners\OrderEventSubscriber@onOrderAudited'
        ],
        'App\Events\OrderDelivered' => [
//            'App\Listeners\OrderEventSubscriber@onOrderDelivered'
        ],
        'App\Events\OrderReceived' => [
//            'App\Listeners\OrderEventSubscriber@onOrderReceived'
        ],
        'App\Events\OrderFailed' => [
//            'App\Listeners\OrderEventSubscriber@onOrderFailed'
        ],
    ];

    protected $subscribe = [
        'App\Listeners\OrderEventSubscriber',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
