<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/12
 * Time: 10:25
 */

namespace App\Models;


/**
 * App\Models\Partner
 *
 * @property int $id
 * @property int $image_id
 * @property string $url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Image $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Partner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Partner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Partner whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Partner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Partner whereUrl($value)
 * @mixin \Eloquent
 */
class Partner extends Model
{
    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}