<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/18
 * Time: 21:38
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class HotKeywordCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => HotKeywordResource::collection($this->collection),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}