<?php

namespace App\Listeners;

use App\Events\UserAccumulatePointsIncome;
use App\Models\MemberLevel;
use App\Models\Message;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserAccumulatePointsIncomeListener
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
     * @param  UserAccumulatePointsIncome  $event
     * @return void
     */
    public function handle(UserAccumulatePointsIncome $event)
    {
        $user = $event->user;

        $userAccumulatePoints = $user->history_accumulate_points;

        $memberLevel = MemberLevel::where('accumulate_points', '<=', $userAccumulatePoints)
            ->orderBy('id', 'desc')
            ->first();

        if ($memberLevel->id > $user->member_level_id) {
            $user->update(['member_level_id' => $memberLevel->id]);

            Message::memberLevelUp($user->id, $memberLevel);
        }
    }
}
