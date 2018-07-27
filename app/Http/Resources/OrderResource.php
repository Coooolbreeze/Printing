<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/27
 * Time: 17:33
 */

namespace App\Http\Resources;


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
            'order_no' => $this->order_no,
            'user' => (new UserResource($this->user))->show(['id', 'nickname']),
            'address' => json_decode($this->snap_address, true),
            'content' => json_decode($this->snap_content, true),
            'status' => $this->convertStatus($this->status),
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertStatus($value)
    {
        $status = [
            0 => '已失效',
            1 => '待支付',
            2 => '待审核',
            3 => '待发货',
            4 => '已发货',
            5 => '已收货'
        ];
        return $status[$value];
    }
}