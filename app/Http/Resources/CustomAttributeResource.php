<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/4
 * Time: 17:54
 */

namespace App\Http\Resources;


class CustomAttributeResource extends Resource
{
    public function toArray($request)
    {
        return [
            'attribute' => $this->name,
            'values' => CustomValueResource::collection($this->values)
        ];
    }
}