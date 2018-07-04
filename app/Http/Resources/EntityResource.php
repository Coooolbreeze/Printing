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
            'custom_number' => $this->custom_number,
            'title' => $this->title,
            'keywords' => $this->keywords,
            'describe' => $this->describe,
            'specs' => AttributeResource::collection($this->attributes),
            'custom_specs' => CustomAttributeResource::collection($this->customAttributes),
            'combinations' => CombinationResource::collection($this->combinations),
            'created_at' => (string)$this->created_at
        ]);
    }
}