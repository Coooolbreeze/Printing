<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/4
 * Time: 9:18
 */

namespace App\Models;


/**
 * App\Models\LargeCategory
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategory whereUpdatedAt($value)
 * @property string $image_id
 * @property string|null $url
 * @property-read \App\Models\Image $icon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LargeCategoryItem[] $items
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategory whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LargeCategory whereUrl($value)
 * @property-read \App\Models\Image $image
 */
class LargeCategory extends Model
{
    public function categories()
    {
        return $this->hasMany('App\Models\Category');
    }

    public function items()
    {
        return $this->hasMany('App\Models\LargeCategoryItem');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}