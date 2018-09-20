<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 0:22
 */

namespace App\Http\Resources;


class SceneResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new SceneResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'describe' => $this->describe,
            'is_open' => (bool)$this->is_open,
            'categories' => SceneCategoryResource::collection($this->sceneCategories),
            'created_at' => (string)$this->created_at
        ]);
    }
}