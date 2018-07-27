<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/12
 * Time: 17:42
 */

namespace App\Models;

use App\Exceptions\BaseException;


/**
 * App\Models\Express
 *
 * @property int $id
 * @property string $name
 * @property float $first_weight
 * @property float $additional_weight
 * @property float $capped
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExpressRegion[] $regions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Express whereAdditionalWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Express whereCapped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Express whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Express whereFirstWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Express whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Express whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Express whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Express extends Model
{
    public function regions()
    {
        return $this->hasMany('App\Models\ExpressRegion');
    }

    /**
     * @param $expressId
     * @param $province
     * @param $totalPrice
     * @param $totalWeight
     * @return float|int|mixed
     * @throws BaseException
     */
    public static function getFreight($expressId, $province, $totalPrice, $totalWeight)
    {
        $express = self::findOrFail($expressId);
        $regions = $express->regions()->pluck('name')->toArray();
        if (!in_array($province, $regions)) throw new BaseException('该区域不支持' . $express->name);

        $freeExpress = config('setting.free_express');
        $firstWeight = config('setting.first_weight');
        $additionalWeight = config('setting.additional_weight');

        if ($freeExpress >= 0 && $totalPrice >= $freeExpress) return 0;
        if ($totalWeight <= $firstWeight) return $express->first_weight;

        $price = ceil(($totalWeight - $firstWeight) / $additionalWeight) * $express->additional_weight + $express->first_weight;
        return ($price >= $express->capped) ? $express->capped : $price;
    }
}