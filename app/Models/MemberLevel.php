<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/20
 * Time: 17:18
 */

namespace App\Models;


/**
 * App\Models\MemberLevel
 *
 * @property int $accumulate_points 所需积分
 * @property \Carbon\Carbon|null $created_at
 * @property float $discount 折扣率
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberLevel whereAccumulatePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberLevel whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberLevel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberLevel whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */
class MemberLevel extends Model
{
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}