<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 13:11
 */

namespace App\Http\Resources;


class LargeCategoryResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->when($this->url, $this->url),
            'categories' => CategoryResource::collection($this->categories)
        ]);
    }
}