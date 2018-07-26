<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 14:33
 */

namespace App\Models;


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
}