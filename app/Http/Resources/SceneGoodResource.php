<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 0:24
 */

namespace App\Http\Resources;


class SceneGoodResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'image' => new ImageResource($this->image),
            'name' => $this->name,
            'describe' => $this->describe,
            'url' => $this->url
        ]);
    }
}