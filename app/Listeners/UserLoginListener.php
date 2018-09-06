<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Exceptions\BaseException;
use App\Models\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserLoginListener
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
     * @param UserLogin $event
     * @throws \App\Exceptions\TokenException
     */
    public function handle(UserLogin $event)
    {
        $user = $event->user;

        if ($user->is_admin) {
            Log::write('登录后台', $user->nickname);
        }
    }
}
