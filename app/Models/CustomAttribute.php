<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/4
 * Time: 17:27
 */

namespace App\Models;


/**
 * App\Models\CustomAttribute
 *
 * @property \Carbon\Carbon|null $created_at
 * @property int $entity_id
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Entity $entity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CustomValue[] $values
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomAttribute whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CustomAttribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomAttribute extends Model
{
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function values()
    {
        return $this->hasMany(CustomValue::class);
    }
}