<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/15
 * Time: 9:36
 */

namespace App\Models;


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

}