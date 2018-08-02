<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedListener
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
     * @param UserCreated $event
     * @throws \App\Exceptions\TokenException
     */
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        if (!$user->is_admin) {
            Message::welcome($user->id);
        }
    }
}
