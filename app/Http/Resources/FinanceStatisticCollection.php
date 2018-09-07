<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/7
 * Time: 12:47
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class FinanceStatisticCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => FinanceStatisticResource::collection($this->collection),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}