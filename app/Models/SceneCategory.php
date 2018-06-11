<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/10
 * Time: 23:46
 */

namespace App\Models;


/**
 * App\Models\SceneCategory
 *
 * @property int $id
 * @property int $scene_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SceneGood[] $sceneGoods
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneCategory whereSceneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SceneCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SceneCategory extends Model
{
    public function sceneGoods()
    {
        return $this->hasMany('App\Models\SceneGood');
    }
}