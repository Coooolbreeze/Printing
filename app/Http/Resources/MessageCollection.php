<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/2
 * Time: 11:20
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => MessageResource::collection($this->collection)->hide(['body']),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}