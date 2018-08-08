<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/1
 * Time: 14:58
 */

namespace App\Models;


/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $commentable_id
 * @property string $commentable_type
 * @property string $body
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereUserId($value)
 * @mixin \Eloquent
 * @property float|null $grade
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereGrade($value)
 * @property string|null $target
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereTarget($value)
 * @property float|null $describe_grade
 * @property string $goods_comment
 * @property int $is_anonymous
 * @property float|null $logistics_grade
 * @property float|null $seller_grade
 * @property string $service_comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereDescribeGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereGoodsComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereLogisticsGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereSellerGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereServiceComment($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 */
class Comment extends Model
{
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function images()
    {
        return $this->belongsToMany('App\Models\Image');
    }
}