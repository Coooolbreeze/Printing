<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/25
 * Time: 17:28
 */

namespace App\Models;


use App\Exceptions\BaseException;
use App\Services\Tokens\TokenFactory;

/**
 * App\Models\BalanceRecord
 *
 * @property \Carbon\Carbon|null $created_at
 * @property string $describe
 * @property int $id
 * @property int $number
 * @property int $surplus
 * @property int $type 1收入 2支出
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BalanceRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BalanceRecord whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BalanceRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BalanceRecord whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BalanceRecord whereSurplus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BalanceRecord whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BalanceRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BalanceRecord whereUserId($value)
 * @mixin \Eloquent
 */
class BalanceRecord extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @param $number
     * @param $describe
     * @param null $user
     * @throws \Throwable
     */
    public static function income($number, $describe, $user = null)
    {
        (!$user) && $user = TokenFactory::getCurrentUser();

        \DB::transaction(function () use ($number, $describe, $user) {
            $user->increment('balance', $number);

            self::create([
                'user_id' => $user->id,
                'number' => $number,
                'surplus' => $user->balance,
                'describe' => $describe,
                'type' => 1
            ]);
        });
    }

    /**
     * @param $number
     * @param $describe
     * @param null $user
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    public static function expend($number, $describe, $user = null)
    {
        (!$user) && $user = TokenFactory::getCurrentUser();

        if ($user->balance < $number)
            throw new BaseException('可用余额不足');

        \DB::transaction(function () use ($number, $describe, $user) {
            $user->decrement('balance', $number);

            self::create([
                'user_id' => $user->id,
                'number' => $number,
                'surplus' => $user->balance,
                'describe' => is_array($describe) ? $describe['describe'] : $describe,
                'order_no' => is_array($describe) ? $describe['order_no'] : null,
                'type' => 2
            ]);
        });
    }
}