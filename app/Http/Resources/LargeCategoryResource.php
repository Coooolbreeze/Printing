<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 13:11
 */

namespace App\Http\Resources;


class LargeCategoryResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new LargeCategoryResourceCollection($resource), function ($collection) {
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
            'icon' => new ImageResource($this->image),
            'name' => $this->name,
            'url' => $this->when($this->url, $this->url),
            'items' => LargeCategoryItemResource::collection($this->items),
            'categories' => CategoryResource::collection($this->categories)
        ]);
    }
}