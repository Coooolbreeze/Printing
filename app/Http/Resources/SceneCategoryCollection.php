<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/23
 * Time: 10:54
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class SceneCategoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => SceneCategoryResource::collection($this->collection)->show(['id', 'name']),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}