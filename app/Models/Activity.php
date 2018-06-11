<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/10
 * Time: 23:48
 */

namespace App\Models;


/**
 * App\Models\Activity
 *
 * @property int $id
 * @property int $image_id
 * @property string $name
 * @property string $describe
 * @property string $finished_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entity[] $entities
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereStatus($value)
 * @property-read \App\Models\Image $image
 */
class Activity extends Model
{
    public function entities()
    {
        return $this->belongsToMany('App\Models\Entity');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}