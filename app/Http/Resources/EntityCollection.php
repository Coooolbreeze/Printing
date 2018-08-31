<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 14:21
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class EntityCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => EntityResource::collection($this->collection)->show(['id', 'image', 'name', 'type', 'summary', 'status', 'sales', 'price', 'comment_count']),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}