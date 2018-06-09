<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 13:11
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class LargeCategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}