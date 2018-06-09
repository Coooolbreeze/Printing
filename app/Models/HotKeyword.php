<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/3
 * Time: 23:55
 */

namespace App\Models;


/**
 * App\Models\HotKeyword
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $sort
 * @property int $status
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotKeyword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotKeyword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotKeyword whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotKeyword whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotKeyword whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotKeyword whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HotKeyword whereUrl($value)
 */
class HotKeyword extends Model
{

}