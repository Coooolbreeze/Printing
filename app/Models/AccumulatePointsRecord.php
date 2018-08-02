<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/24
 * Time: 12:21
 */

namespace App\Models;


use App\Events\UserAccumulatePointsIncome;
use App\Exceptions\BaseException;
use App\Services\Tokens\TokenFactory;

/**
 * App\Models\AccumulatePointsRecord
 *
 * @property int $id
 * @property int $user_id
 * @property int $number
 * @property int $surplus
 * @property string $describe
 * @property int $type 1收入 2支出
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatePointsRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatePointsRecord whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatePointsRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatePointsRecord whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatePointsRecord whereSurplus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatePointsRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatePointsRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccumulatePointsRecord whereUserId($value)
 * @mixin \Eloquent
 */
class AccumulatePointsRecord extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @param $number
     * @param $describe
     * @param User|null $user
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    public static function income($number, $describe, User $user = null)
    {
        (!$user) && $user = TokenFactory::getCurrentUser();

        \DB::transaction(function () use ($number, $describe, $user) {
            $user->increment('accumulate_points', $number);
            $user->increment('history_accumulate_points', $number);

            self::create([
                'user_id' => $user->id,
                'number' => $number,
                'surplus' => $user->accumulate_points,
                'describe' => $describe,
                'type' => 1
            ]);

            event(new UserAccumulatePointsIncome($user));
        });
    }

    /**
     * @param $number
     * @param $describe
     * @param User|null $user
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    public static function expend($number, $describe, User $user = null)
    {
        (!$user) && $user = TokenFactory::getCurrentUser();

        if ($user->accumulate_points < $number)
            throw new BaseException('可用积分不足');

        \DB::transaction(function () use ($number, $describe, $user) {
            $user->decrement('accumulate_points', $number);

            self::create([
                'user_id' => $user->id,
                'number' => $number,
                'surplus' => $user->accumulate_points,
                'describe' => $describe,
                'type' => 2
            ]);
        });
    }
}