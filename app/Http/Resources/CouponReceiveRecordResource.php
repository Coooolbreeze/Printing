<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/27
 * Time: 23:53
 */

namespace App\Http\Resources;


class CouponReceiveRecordResource extends Resource
{
    public function toArray($request)
    {
        return [
            'phone' => $this->partialHidden($this->user->phone, 3, 4),
            'coupon' => $this->name,
            'created_at' => (string)$this->created_at
        ];
    }

    public function partialHidden($value, $start, $length)
    {
        if (!$value) {
            return $value;
        }

        return substr_replace($value, '****', $start, $length);
    }
}