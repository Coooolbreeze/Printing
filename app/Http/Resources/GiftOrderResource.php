<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/25
 * Time: 11:23
 */

namespace App\Http\Resources;


class GiftOrderResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new GiftOrderResourceCollection($resource), function ($collection) {
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
            'content' => $this->convertContent($this->snap_content),
            'express_company' => $this->when($this->status == 2, $this->express_company),
            'tracking_no' => $this->when($this->status == 2, $this->tracking_no),
            'status' => $this->convertStatus($this->status),
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertContent($content)
    {
        $content = json_decode($content, true);
        $content['image'] = config('app.url') . '/storage/' . $content['image'];
        return $content;
    }

    public function convertStatus($value)
    {
        $status = [1 => '未发货', 2 => '已发货'];
        return $status[$value];
    }
}