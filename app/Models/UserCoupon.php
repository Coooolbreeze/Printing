<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 14:33
 */

namespace App\Models;

use App\Exceptions\BaseException;
use App\Services\Tokens\TokenFactory;
use Carbon\Carbon;


/**
 * App\Models\UserCoupon
 *
 * @property string $coupon_no
 * @property \Carbon\Carbon|null $created_at
 * @property string $finished_at
 * @property int $id
 * @property int $is_meanwhile
 * @property int $is_used
 * @property string $name
 * @property float $quota
 * @property float|null $satisfy
 * @property int $type 1满减 2抵扣
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereCouponNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereIsMeanwhile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereIsUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereSatisfy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserCoupon whereUserId($value)
 * @mixin \Eloquent
 */
class UserCoupon extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeActive($query)
    {
        return $query->where('is_used', 0)->whereDate('finished_at', '>', Carbon::now());
    }

    /**
     * @param $couponNo
     * @param $price
     * @return mixed
     * @throws BaseException
     */
    public static function use($couponNo, $price)
    {
        $coupon = self::where('coupon_no', $couponNo)
            ->where('user_id', TokenFactory::getCurrentUID())
            ->lockForUpdate()
            ->firstOrFail();

        if ($coupon->is_used == 1) throw new BaseException('该优惠券已被使用');
        if ($coupon->finished_at < Carbon::now()) throw new BaseException('该优惠券已过期');
        if ($coupon->type == 1 && $coupon->satisfy > $price) throw new BaseException('不满足该优惠券使用条件');
        if ($coupon->is_disabled == 1) throw new BaseException('该优惠券暂时无法使用');

        $coupon->update(['is_used' => 1]);

        return $coupon->quota;
    }
}