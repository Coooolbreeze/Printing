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
            'entity' => (new EntityResource(Entity::findOrFail($this->entity_id)))->show(['id', 'image', 'name']),
            'file' => $this->when($this->file_id, function () {
                return new FileResource(File::findOrFail($this->file_id));
            }),
            'specs' => $this->count ? json_decode($this->specs, true) : array_slice(json_decode($this->specs, true), 0, -1),
            'custom_specs' => json_decode($this->custom_specs, true),
            'count' => $this->count ?: json_decode($this->specs, true)['数量'],
            'price' => $this->price,
            'remark' => $this->remark
        ];
    }
}