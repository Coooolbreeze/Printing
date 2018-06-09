<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/1
 * Time: 9:50
 */

namespace App\Models;


/**
 * App\Models\CommonValue
 *
 * @property-read \App\Models\CommonAttribute $commonValue
 * @mixin \Eloquent
 * @property int $id
 * @property int $common_attribute_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonValue whereCommonAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonValue whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonValue whereUpdatedAt($value)
 */
class CommonValue extends Model
{
    public function commonValue()
    {
        return $this->belongsTo('App\Models\CommonAttribute');
    }
}