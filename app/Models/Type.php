<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 14:21
 */

namespace App\Models;


/**
 * App\Models\Type
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entity[] $entities
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SecondaryType[] $secondaryTypes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Type whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Type extends Model
{
    public function secondaryTypes()
    {
        return $this->hasMany('App\Models\SecondaryType');
    }

    public function entities()
    {
        return $this->hasMany('App\Models\Entity');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}