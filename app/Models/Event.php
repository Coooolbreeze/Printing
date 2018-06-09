<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/3
 * Time: 23:55
 */

namespace App\Models;


/**
 * App\Models\Event
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property int $image_id
 * @property string $url
 * @property int $sort
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereUrl($value)
 */
class Event extends Model
{

}