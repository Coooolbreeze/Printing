<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/3
 * Time: 23:57
 */

namespace App\Models;


/**
 * App\Models\Scene
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $describe
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scene whereUpdatedAt($value)
 */
class Scene extends Model
{

}