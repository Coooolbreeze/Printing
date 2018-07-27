<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/19
 * Time: 11:05
 */

namespace App\Http\Resources;


use App\Models\Entity;
use App\Models\File;

class CartResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'entity' => (new EntityResource($this->entity))->show(['id', 'image', 'name', 'lead_time']),
            'file' => $this->when($this->file_id, function () {
                return new FileResource($this->file);
            }),
            'specs' => $this->count ? json_decode($this->specs, true) : array_slice(json_decode($this->specs, true), 0, -1),
            'custom_specs' => json_decode($this->custom_specs, true),
            'count' => $this->count ?: json_decode($this->specs, true)['数量'],
            'price' => $this->price,
            'weight' => $this->weight,
            'remark' => $this->remark
        ];
    }
}