<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 14:21
 */

namespace App\Http\Resources;


class EntityResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new EntityResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'images' => ImageResource::collection($this->images),
            'name' => $this->name,
            'summary' => $this->summary,
            'body' => $this->body,
            'lead_time' => $this->lead_time,
            'attributes' => AttributeResource::collection($this->attributes),
            'combinations' => CombinationResource::collection($this->combinations),
            'created_at' => (string)$this->created_at
        ]);
    }
}