<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 14:22
 */

namespace App\Models;


/**
 * App\Models\SecondaryType
 *
 * @property int $id
 * @property int $type_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entity[] $entities
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SecondaryType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SecondaryType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SecondaryType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SecondaryType whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SecondaryType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SecondaryType extends Model
{
    public function entities()
    {
        return $this->hasMany('App\Models\Entity');
    }
}