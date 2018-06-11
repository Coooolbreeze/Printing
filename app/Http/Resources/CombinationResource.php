<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 15:39
 */

namespace App\Http\Resources;


class CombinationResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'combination' => $this->combination,
            'price' => $this->price,
            'weight' => $this->weight
        ];
    }
}