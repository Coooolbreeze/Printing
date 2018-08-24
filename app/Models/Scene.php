<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/3
 * Time: 23:57
 */

namespace App\Models;


use App\Services\Tokens\TokenFactory;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Scene
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $describe
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereUpdatedAt($value)
 * @property int $image_id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereName($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SceneCategory[] $sceneCategories
 * @property-read \App\Models\Image $image
 */
class Scene extends Model
{
    protected static function boot()
    {
        parent::boot();

        if (!TokenFactory::isAdmin()) {
            static::addGlobalScope('is_open', function (Builder $builder) {
                $builder->where('is_open', 1);
            });
        }
    }

    public function sceneCategories()
    {
        return $this->hasMany('App\Models\SceneCategory');
    }
}