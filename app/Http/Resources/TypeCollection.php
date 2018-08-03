<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 15:53
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => TypeResource::collection($this->collection)->hide(['entities', 'secondary_types']),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}