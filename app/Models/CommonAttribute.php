<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/1
 * Time: 0:42
 */

namespace App\Models;


/**
 * App\Models\CommonAttribute
 *
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CommonValue[] $commonValue
 * @mixin \Eloquent
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property int $is_multiple
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonAttribute whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonAttribute whereIsMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonAttribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CommonAttribute whereUpdatedAt($value)
 */
class CommonAttribute extends Model
{
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function commonValue()
    {
        return $this->hasMany('App\Models\CommonValue');
    }
}