<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/15
 * Time: 9:36
 */

namespace App\Models;


use App\Exceptions\BaseException;
use App\Services\Tokens\TokenFactory;

/**
 * App\Models\Coupon
 *
 * @property int $id
 * @property string $name
 * @property int $type 1满减 2抵扣
 * @property float $quota
 * @property float|null $satisfy
 * @property int $number
 * @property int $received
 * @property int $is_meanwhile
 * @property string $finished_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereIsMeanwhile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereSatisfy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $coupon_no
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coupon whereCouponNo($value)
 */
class Coupon extends Model
{
    /**
     * 领取优惠券
     *
     * @param $couponNo
     * @param null $user
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    public static function receive($couponNo, $user = null)
    {
        if (!$user) $user = TokenFactory::getCurrentUser();

        \DB::transaction(function () use ($couponNo, $user) {
            $coupon = Coupon::where('coupon_no', $couponNo)
                ->lockForUpdate()
                ->firstOrFail();

            $received = $user->receivedCoupons()->pluck('id')->toArray();
            if (in_array($coupon->id, $received)) throw new BaseException('已经领取，不可重复领取');
            if ($coupon->received >= $coupon->number) throw new BaseException('该优惠券已被领完');
            if ($coupon->is_disabled == 1) throw new BaseException('暂时无法领取');

            UserCoupon::create([
                'user_id' => $user->id,
                'coupon_id' => $coupon->id,
                'coupon_no' => $coupon->coupon_no,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'quota' => $coupon->quota,
                'satisfy' => $coupon->satisfy,
                'is_meanwhile' => $coupon->is_meanwhile,
                'finished_at' => $coupon->finished_at
            ]);

            $user->receivedCoupons()->attach($coupon->id);
            $coupon->increment('received', 1);
        });
    }
}