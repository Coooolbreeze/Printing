<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 0:23
 */

namespace App\Http\Resources;


class SceneCategoryResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'name' => $this->name,
            'goods' => SceneGoodResource::collection($this->sceneGoods)
        ]);
    }
}