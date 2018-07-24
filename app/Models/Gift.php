<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/23
 * Time: 16:04
 */

namespace App\Models;


/**
 * App\Models\Gift
 *
 * @property int $id
 * @property int $image_id
 * @property int $accumulate_points
 * @property string $name
 * @property string $detail
 * @property int $stock
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Image $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gift whereAccumulatePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gift whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gift whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gift whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gift whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Gift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Gift extends Model
{
    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}