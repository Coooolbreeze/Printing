<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/24
 * Time: 16:00
 */

namespace App\Http\Resources;


class AddressResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'province' => $this->province,
            'city' => $this->city,
            'county' => $this->county,
            'detail' => $this->detail,
            'is_default' => (bool)$this->is_default
        ];
    }
}