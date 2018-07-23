<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/23
 * Time: 16:25
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class GiftCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => GiftResource::collection($this->collection)->hide(['detail']),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}