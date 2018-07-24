<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/24
 * Time: 15:12
 */

namespace App\Http\Resources;


class AccumulatePointsRecordResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new AccumulatePointsRecordResourceCollection($resource), function ($collection) {
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
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertType($value)
    {
        $type = [1 => '收入', 2 => '支出'];

        return $type[$value];
    }
}