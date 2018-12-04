<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/3
 * Time: 16:18
 */

namespace App\Http\Resources;


class OrderApplyResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'user' => (new UserResource($this->user))->show(['id', 'nickname']),
            'order_no' => $this->order_no,
            'price' => $this->price,
            'channel' => $this->channel,
            'type' => $this->convertType($this->type),
            'status' => $this->convertStatus($this->status),
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertType($value)
    {
        $type = [
            1 => '退款',
            2 => '后台支付'
        ];
        return $type[$value];
    }

    public function convertStatus($value)
    {
        $status = [
            0 => '未处理',
            1 => '已同意',
            2 => '已驳回'
        ];
        return $status[$value];
    }
}