<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/21
 * Time: 15:37
 */

namespace App\Http\Resources;


class BannerResource extends Resource
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