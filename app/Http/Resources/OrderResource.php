<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/27
 * Time: 17:33
 */

namespace App\Http\Resources;


use App\Enum\OrderPayTypeEnum;
use App\Enum\OrderStatusEnum;

class OrderResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new OrderResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'receipt_id' => $this->receipt_id,
            'order_no' => $this->order_no,
            'user' => (new UserResource($this->user))->show(['id', 'nickname']),
            'title' => $this->title,
            'address' => json_decode($this->snap_address, true),
            'content' => json_decode($this->snap_content, true),
            'status' => $this->convertStatus($this->status),
            'pay_type' => $this->when($this->status > 1, $this->convertPayType($this->pay_type)),
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertStatus($value)
    {
        $status = [
            OrderStatusEnum::EXPIRE => '已失效',
            OrderStatusEnum::UNPAID => '待支付',
            OrderStatusEnum::PAID => '已支付',
            OrderStatusEnum::UNDELIVERED => '待发货',
            OrderStatusEnum::DELIVERED => '已发货',
            OrderStatusEnum::RECEIVED => '已收货',
            OrderStatusEnum::COMMENTED => '已评论'
        ];
        return $status[$value];
    }

    public function convertPayType($value)
    {
        $payType = [
            OrderPayTypeEnum::ALI_PAY => '支付宝支付',
            OrderPayTypeEnum::WX_PAY => '微信支付',
            OrderPayTypeEnum::BALANCE => '余额支付',
            OrderPayTypeEnum::BACK_PAY => '后台支付'
        ];
        return $payType[$value];
    }
}