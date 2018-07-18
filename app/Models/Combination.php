<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/4
 * Time: 14:22
 */

namespace App\Models;

use App\Exceptions\BaseException;


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
 * @property float|null $weight
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Combination whereWeight($value)
 */
class Combination extends Model
{
    public static function isSpecMatch($combinationId, $spec)
    {
        $combination = '';
        foreach ($spec as $value) {
            if ($combination === '') $combination .= $value;
            else $combination .= config('setting.sku_separator') . $value;
        }

        return self::findOrFail($combinationId)->combination === $combination;
    }

    public static function isPriceMatch($combinationId, $price, $customSpecs = [], $count = 0)
    {
        $specPrice = self::findOrFail($combinationId)->price;
        foreach ($customSpecs as $customSpec)
            foreach ($customSpec as $value)
                $specPrice *= $value;

        if ($count != 0) $specPrice *= $count;

        return $specPrice == $price;
    }
}