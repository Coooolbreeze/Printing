<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/7
 * Time: 14:41
 */

namespace App\Http\Resources;


class RecommendOtherResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'entity' => (new EntityResource($this->entity))->show(['id', 'name'])
        ];
    }
}