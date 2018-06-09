<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 16:15
 */

namespace App\Http\Resources;


class HelpCategoryResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new HelpCategoryResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'helps' => HelpResource::collection($this->helps)->show(['id', 'title'])
        ]);
    }
}