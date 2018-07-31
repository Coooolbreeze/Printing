<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 15:59
 */

namespace App\Models;


use App\Services\Tokens\TokenFactory;

/**
 * App\Models\OrderLog
 *
 * @property string $action
 * @property string $administrator
 * @property \Carbon\Carbon|null $created_at
 * @property int $id
 * @property int $order_id
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderLog whereAdministrator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderLog whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderLog extends Model
{
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public static function write($orderId, $action)
    {
        return self::create([
            'order_id' => $orderId,
            'administrator' => TokenFactory::getCurrentUser()->nickname,
            'action' => $action
        ]);
    }
}