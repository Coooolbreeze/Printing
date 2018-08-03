<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 14:21
 */

namespace App\Models;


/**
 * App\Models\CategoryItem
 *
 * @property int $id
 * @property int $item_id
 * @property int $item_type 1类型 2商品
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Category $Category
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryItem whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CategoryItem whereCategoryId($value)
 */
class CategoryItem extends Model
{
    public function Category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function type()
    {
        return $this->hasOne('App\Models\Type', 'id', 'item_id');
    }

    public function entity()
    {
        return $this->hasOne('App\Models\Entity', 'id', 'item_id');
    }
}