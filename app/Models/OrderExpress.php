<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 16:00
 */

namespace App\Models;


/**
 * App\Models\OrderExpress
 *
 * @property string $company
 * @property \Carbon\Carbon|null $created_at
 * @property int $id
 * @property int $order_id
 * @property string $tracking_no
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderExpress whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderExpress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderExpress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderExpress whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderExpress whereTrackingNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderExpress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderExpress extends Model
{
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}