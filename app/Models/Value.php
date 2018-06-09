<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/4
 * Time: 0:00
 */

namespace App\Models;


/**
 * App\Models\Value
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $attribute_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Value whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Value whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Value whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Value whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Value whereUpdatedAt($value)
 */
class Value extends Model
{

}