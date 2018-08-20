<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/20
 * Time: 17:33
 */

namespace App\Http\Resources;


class MemberLevelResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'icon' => new ImageResource($this->image),
            'name' => $this->name,
            'accumulate_points' => $this->accumulate_points,
            'discount' => $this->discount
        ];
    }
}