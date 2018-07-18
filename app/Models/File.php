<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/18
 * Time: 16:04
 */

namespace App\Models;


/**
 * App\Models\File
 *
 * @property \Carbon\Carbon|null $created_at
 * @property int $id
 * @property string $src
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\File whereName($value)
 */
class File extends Model
{

}