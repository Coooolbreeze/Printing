<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 17:58
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryItemCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => CategoryItemResource::collection($this->collection),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}