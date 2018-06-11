<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 0:22
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class SceneCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => SceneResource::collection($this->collection)->show(['id', 'name', 'describe']),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}