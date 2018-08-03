<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 15:30
 */

namespace App\Http\Resources;


use App\Models\Entity;

class TypeResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new TypeResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'secondary_types' => SecondaryTypeResource::collection($this->secondaryTypes),
            'entities' => new EntityCollection($this->entities()->paginate(Entity::getLimit()))
        ]);
    }
}