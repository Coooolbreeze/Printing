<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 14:39
 */

namespace App\Http\Resources;


class AttributeResource extends Resource
{
    public function toArray($request)
    {
        return [
            'attribute' => $this->name,
            'values' => $this->values()->pluck('name')
        ];
    }
}