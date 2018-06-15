<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/15
 * Time: 10:00
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class CouponCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => CouponResource::collection($this->collection),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}