<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/14
 * Time: 17:59
 */

namespace App\Http\Resources;


use App\Enum\CouponTypeEnum;

class CouponResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->convertType($this->type),
            'quota' => $this->quota,
            'satisfy' => $this->when($this->type == CouponTypeEnum::FULL_SUBTRACTION, $this->satisfy),
            'number' => $this->number,
            'surplus' => $this->number - $this->received,
            'is_meanwhile' => (bool)$this->is_meanwhile,
            'finished_at' => (string)$this->finished_at,
            'created_at' => (string)$this->created_at
        ];
    }

    public function convertType($value)
    {
        $type = [
            CouponTypeEnum::FULL_SUBTRACTION => '满减',
            CouponTypeEnum::DEDUCTIBLE => '抵扣'
        ];
        return $type[$value];
    }
}