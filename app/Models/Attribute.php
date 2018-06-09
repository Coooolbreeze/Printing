<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/31
 * Time: 16:45
 */

namespace App\Models;


/**
 * App\Models\Attribute
 *
 * @property-read \App\Models\Entity $entity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Value[] $values
 * @mixin \Eloquent
 * @property int $id
 * @property int $entity_id
 * @property string $name
 * @property int $is_multiple
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attribute whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attribute whereIsMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Attribute whereUpdatedAt($value)
 */
class Attribute extends Model
{
    public function entity()
    {
        return $this->belongsTo('App\Models\Entity');
    }

    public function values()
    {
        return $this->hasMany('App\Models\Value');
    }
}