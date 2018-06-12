<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/12
 * Time: 10:26
 */

namespace App\Http\Resources;


class PartnerResource extends Resource
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