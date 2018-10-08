<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/13
 * Time: 9:16
 */

namespace App\Http\Resources;


class ExpressResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'first_unity' => config('setting.first_weight'),
            'additional_unity' => config('setting.additional_weight'),
            'first_weight' => $this->first_weight,
            'additional_weight' => $this->additional_weight,
            'free_express' => config('setting.free_express'),
            'capped' => $this->capped,
            'regions' => $this->regions()->pluck('name')
        ]);
    }
}