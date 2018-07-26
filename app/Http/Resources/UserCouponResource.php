<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 14:37
 */

namespace App\Http\Resources;


use App\Enum\CouponTypeEnum;
use Carbon\Carbon;

class UserCouponResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'coupon_no' => $this->coupon_no,
            'name' => $this->name,
            'type' => $this->convertType($this->type),
            'quota' => $this->quota,
            'satisfy' => $this->when($this->type == CouponTypeEnum::FULL_SUBTRACTION, $this->satisfy),
            'is_meanwhile' => (bool)$this->is_meanwhile,
            'status' => $this->status(),
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

    public function status()
    {
        if ($this->is_used == 1)
            return '已使用';
        else if ($this->finished_at < Carbon::now())
            return '已过期';
        else
            return '可使用';
    }
}