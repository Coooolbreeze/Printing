<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/23
 * Time: 16:25
 */

namespace App\Http\Resources;


class GiftResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new GiftResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'image' => new ImageResource($this->image),
            'accumulate_points' => $this->accumulate_points,
            'detail' => $this->accumulate_points,
            'stock' => $this->stock
        ]);
    }
}