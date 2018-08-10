<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/8
 * Time: 11:27
 */

namespace App\Http\Resources;


use App\Enum\OrderPayTypeEnum;

class RechargeOrderResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => (new UserResource($this->user))->show(['id', 'nickname']),
            'order_no' => $this->order_no,
            'price' => $this->price,
//            'is_paid' => (bool)$this->is_paid,
            'pay_type' => $this->pay_type == OrderPayTypeEnum::ALI_PAY ? '支付宝' : '微信支付',
            'created_at' => (string)$this->created_at
        ];
    }
}