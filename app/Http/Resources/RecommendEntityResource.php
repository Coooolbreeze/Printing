<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/21
 * Time: 16:46
 */

namespace App\Http\Resources;


class RecommendEntityResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => new ImageResource($this->image),
            'url' => $this->url
        ];
    }
}