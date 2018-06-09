<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/1
 * Time: 0:33
 */

namespace App\Models;


/**
 * App\Models\Category
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entity[] $entities
 * @property-read \App\Models\LargeCategory $largeCategory
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $large_category_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereLargeCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Category whereUpdatedAt($value)
 */
class Category extends Model
{
    public function largeCategory()
    {
        return $this->belongsTo('App\Models\LargeCategory');
    }

    public function entities()
    {
        return $this->hasMany('App\Models\Entity');
    }
}