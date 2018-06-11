<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/10
 * Time: 23:47
 */

namespace App\Models;


/**
 * App\Models\SceneGood
 *
 * @property int $id
 * @property int $scene_category_id
 * @property int $image_id
 * @property string $name
 * @property string $describe
 * @property string $url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneGood whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneGood whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneGood whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneGood whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneGood whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneGood whereSceneCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneGood whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneGood whereUrl($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Image $image
 */
class SceneGood extends Model
{
    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}