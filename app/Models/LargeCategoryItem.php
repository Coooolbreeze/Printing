<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/7
 * Time: 10:07
 */

namespace App\Models;


/**
 * App\Models\LargeCategoryItem
 *
 * @property \Carbon\Carbon|null $created_at
 * @property int $id
 * @property int $is_hot
 * @property int $is_new
 * @property int $item_id
 * @property int $item_type 1类型 2商品
 * @property int $large_category_id
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategoryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategoryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategoryItem whereIsHot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategoryItem whereIsNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategoryItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategoryItem whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategoryItem whereLargeCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategoryItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Entity $entity
 * @property-read \App\Models\Type $type
 */
class LargeCategoryItem extends Model
{
    public function type()
    {
        return $this->hasOne('App\Models\Type', 'id', 'item_id');
    }

    public function entity()
    {
        return $this->hasOne('App\Models\Entity', 'id', 'item_id');
    }

    public function largeCategory()
    {
        return $this->belongsTo('App\Models\LargeCategory');
    }
}