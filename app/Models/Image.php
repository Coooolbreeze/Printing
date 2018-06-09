<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/4
 * Time: 0:00
 */

namespace App\Models;


/**
 * App\Models\Image
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $src
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Image whereUpdatedAt($value)
 */
class Image extends Model
{

}