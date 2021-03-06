<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 15:58
 */

namespace App\Models;


use App\Enum\OrderStatusEnum;
use App\Events\OrderCreated;
use App\Events\OrderUpdated;

/**
 * App\Models\Order
 *
 * @property float $balance_deducted
 * @property string|null $coupon_no
 * @property \Carbon\Carbon|null $created_at
 * @property string|null $delivered_at
 * @property float $discount_amount
 * @property float $freight
 * @property int $goods_count
 * @property float $goods_price
 * @property int $id
 * @property string $order_no
 * @property string|null $paid_at
 * @property int $pay_type 1支付宝 2微信 3余额
 * @property string|null $receipt_id
 * @property string|null $received_at
 * @property string $remark
 * @property string $snap_address
 * @property string $snap_content
 * @property int $status 0已失效 1未支付 2待审核 3待发货 4已发货 5已收货
 * @property float $total_weight
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderExpress[] $expresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderLog[] $logs
 * @property-read \App\Models\Receipt|null $receipt
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereBalanceDeducted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCouponNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereFreight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereGoodsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereSnapAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereSnapContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $audited_at
 * @property string $title
 * @property float $total_price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereAuditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalPrice($value)
 */
class Order extends Model
{
    protected $dispatchesEvents = [
        'created' => OrderCreated::class
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\OrderLog');
    }

    public function express()
    {
        return $this->belongsTo('App\Models\Express');
    }

    public function expresses()
    {
        return $this->hasMany('App\Models\OrderExpress');
    }

    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt');
    }

    public function applies()
    {
        return $this->hasMany('App\Models\OrderApply', 'order_no', 'order_no');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', OrderStatusEnum::UNPAID);
    }

    public function scopePaid($query)
    {
        return $query->where('status', OrderStatusEnum::PAID);
    }

    public function scopeUndelivered($query)
    {
        return $query->where('status', OrderStatusEnum::UNDELIVERED);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', OrderStatusEnum::DELIVERED);
    }

    public function scopeReceived($query)
    {
        return $query->where('status', OrderStatusEnum::RECEIVED);
    }

    public function scopeCommented($query)
    {
        return $query->where('status', OrderStatusEnum::COMMENTED);
    }
}