<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/14
 * Time: 16:55
 */

namespace App\Models;


/**
 * App\Models\FreeExpress
 *
 * @property int $id
 * @property float $price 0表示无邮费减免政策
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FreeExpress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FreeExpress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FreeExpress wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\FreeExpress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FreeExpress extends Model
{

}