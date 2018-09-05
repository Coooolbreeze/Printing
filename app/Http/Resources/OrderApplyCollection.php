<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/3
 * Time: 16:40
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderApplyCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => OrderApplyResource::collection($this->collection),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}