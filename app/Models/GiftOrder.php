<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/24
 * Time: 12:20
 */

namespace App\Models;


/**
 * App\Models\GiftOrder
 *
 * @property int $id
 * @property int $user_id
 * @property int $gift_id
 * @property string $snap_content
 * @property string $snap_address
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Gift $gift
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereGiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereSnapAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereSnapContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereUserId($value)
 * @mixin \Eloquent
 * @property int $status 1未发货 2已发货
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereStatus($value)
 * @property string|null $tracking_no
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftOrder whereTrackingNo($value)
 */
class GiftOrder extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function gift()
    {
        return $this->belongsTo('App\Models\Gift');
    }
}