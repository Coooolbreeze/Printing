<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 16:15
 */

namespace App\Http\Resources;


use App\Models\Entity;

class SecondaryTypeResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'entities' => new EntityCollection($this->entities()->paginate(Entity::getLimit()))
        ]);
    }
}