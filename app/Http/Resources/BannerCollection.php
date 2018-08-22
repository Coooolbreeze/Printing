<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/22
 * Time: 10:29
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => BannerResource::collection($this->collection),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}