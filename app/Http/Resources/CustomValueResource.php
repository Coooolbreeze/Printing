<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/4
 * Time: 17:56
 */

namespace App\Http\Resources;


class CustomValueResource extends Resource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'unit' => $this->unit,
            'min' => $this->min,
            'max' => $this->max,
            'default' => $this->default
        ];
    }
}