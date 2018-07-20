<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/6
 * Time: 16:20
 */

namespace App\Models;


/**
 * App\Models\Cart
 *
 * @property int $count
 * @property \Carbon\Carbon|null $created_at
 * @property int $entity_id
 * @property int|null $file_id
 * @property int $id
 * @property float $price
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereUserId($value)
 * @mixin \Eloquent
 * @property int $combination_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCombinationId($value)
 * @property string $custom_specs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereCustomSpecs($value)
 * @property string $specs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereSpecs($value)
 * @property-read \App\Models\User $user
 * @property string|null $remark
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cart whereRemark($value)
 * @property-read \App\Models\Entity $entity
 * @property-read \App\Models\File $file
 */
class Cart extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function entity()
    {
        return $this->belongsTo('App\Models\Entity');
    }

    public function file()
    {
        return $this->belongsTo('App\Models\File')->withDefault();
    }
}