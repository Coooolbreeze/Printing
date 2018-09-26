<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 16:54
 */

namespace App\Http\Resources;


class CategoryResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new CategoryResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        $this->items->filter(function ($item) {
            return ($item->item_type == 1 || $item->entity);
        });

        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->when($this->url, $this->url),
            'items' => CategoryItemResource::collection($this->items)
        ]);
    }
}