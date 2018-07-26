<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 14:38
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCouponCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => UserCouponResource::collection($this->collection),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}