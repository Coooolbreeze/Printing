<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 16:59
 */

namespace App\Http\Resources;


class CategoryItemResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'type' => $this->item_type,
            'item' => $this->item_type == 1
                ? (new TypeResource($this->type))->show(['id', 'name'])
                : (new EntityResource($this->entity))->show(['id', 'name'])
        ]);
    }
}