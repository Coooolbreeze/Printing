<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/7
 * Time: 10:05
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class LargeCategoryItemCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => LargeCategoryItemResource::collection($this->collection),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}