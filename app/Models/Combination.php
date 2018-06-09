<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/4
 * Time: 14:22
 */

namespace App\Models;


/**
 * App\Models\Combination
 *
 * @property int $id
 * @property int $entity_id
 * @property float $price
 * @property string $combination
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Combination whereCombination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Combination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Combination whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Combination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Combination wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Combination whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Combination extends Model
{

}