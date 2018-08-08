<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/8
 * Time: 11:03
 */

namespace App\Models;


use App\Services\Tokens\TokenFactory;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\RechargeOrder
 *
 * @property \Carbon\Carbon|null $created_at
 * @property int $id
 * @property int $is_paid
 * @property string $order_no
 * @property int $pay_type 1支付宝 2微信
 * @property float $price
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RechargeOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RechargeOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RechargeOrder whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RechargeOrder whereOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RechargeOrder wherePayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RechargeOrder wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RechargeOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RechargeOrder whereUserId($value)
 * @mixin \Eloquent
 */
class RechargeOrder extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('is_paid', function (Builder $builder) {
            $builder->where('is_paid', 1);
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function generate($price)
    {
        return self::create([
            'user_id' => TokenFactory::getCurrentUID(),
            'order_no' => 'R-' . makeOrderNo(),
            'price' => $price,
        ]);
    }
}