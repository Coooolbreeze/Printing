<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/25
 * Time: 17:36
 */

namespace App\Http\Resources;


class BalanceRecordResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new BalanceRecordResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'type' => $this->convertType($this->type),
            'number' => $this->number,
            'surplus' => $this->surplus,
            'describe' => $this->describe,
            'order_no' => $this->when($this->order_no, $this->order_no),
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertType($value)
    {
        $type = [1 => '收入', 2 => '支出'];

        return $type[$value];
    }
}