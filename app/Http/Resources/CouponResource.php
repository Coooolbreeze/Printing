<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/14
 * Time: 17:59
 */

namespace App\Http\Resources;


use App\Enum\CouponTypeEnum;
use App\Services\Tokens\TokenFactory;

class CouponResource extends Resource
{
    public function toArray($request)
    {
        $received = [];
        try {
            $received = TokenFactory::getCurrentUser()->receivedCoupons()->pluck('id')->toArray();
        } catch (\Exception $exception) {
        }

        return [
            'id' => $this->id,
            'coupon_no' => $this->coupon_no,
            'name' => $this->name,
            'type' => $this->convertType($this->type),
            'quota' => $this->quota,
            'satisfy' => $this->when($this->type == CouponTypeEnum::FULL_SUBTRACTION, $this->satisfy),
            'number' => $this->number,
            'surplus' => $this->number - $this->received,
            'is_received' => in_array($this->id, $received),
            'is_meanwhile' => (bool)$this->is_meanwhile,
            'is_disabled' => (bool)$this->is_disabled,
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