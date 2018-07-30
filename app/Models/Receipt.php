<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/30
 * Time: 17:35
 */

namespace App\Models;


/**
 * App\Models\Receipt
 *
 * @property string $address
 * @property string $company
 * @property string $contact
 * @property string $contact_way
 * @property \Carbon\Carbon|null $created_at
 * @property int $id
 * @property string $tax_no
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereContactWay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereTaxNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereUserId($value)
 * @mixin \Eloquent
 */
class Receipt extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }
}