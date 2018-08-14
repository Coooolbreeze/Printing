<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/3
 * Time: 23:57
 */

namespace App\Models;


/**
 * App\Models\News
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $image_id
 * @property string $title
 * @property string $body
 * @property int $sort
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereUpdatedAt($value)
 * @property string $from
 * @property string $summary
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\News whereSummary($value)
 */
class News extends Model
{
    public function newsCategory()
    {
        return $this->belongsTo('App\Models\NewsCategory');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}