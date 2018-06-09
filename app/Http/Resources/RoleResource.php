<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/6
 * Time: 16:02
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'permissions' => $this->permissions()->pluck('name')
        ];
    }
}