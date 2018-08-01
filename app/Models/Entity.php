<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/3
 * Time: 23:59
 */

namespace App\Models;


/**
 * App\Models\Entity
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attribute[] $attributes
 * @property-read \App\Models\Category $category
 * @mixin \Eloquent
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $summary
 * @property string $body
 * @property int $lead_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereLeadTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Combination[] $combinations
 * @property string $title
 * @property string $keywords
 * @property string $describe
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereTitle($value)
 * @property int $custom_number 0不允许自定义 1单人数量 2多人数量
 * @property int $sales
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereCustomNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Entity whereSales($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CustomAttribute[] $customAttributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 */
class Entity extends Model
{
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function attributes()
    {
        return $this->hasMany('App\Models\Attribute');
    }

    public function customAttributes()
    {
        return $this->hasMany('App\Models\CustomAttribute');
    }

    public function images()
    {
        return $this->belongsToMany('App\Models\Image');
    }

    public function combinations()
    {
        return $this->hasMany('App\Models\Combination');
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }
}