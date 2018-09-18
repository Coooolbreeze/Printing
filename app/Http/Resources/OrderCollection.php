<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/27
 * Time: 17:33
 */

namespace App\Http\Resources;


use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \App\Exceptions\TokenException
     */
    public function toArray($request)
    {
        return [
            'data' => OrderResource::collection($this->collection)->hide(['logs']),
            'unpaid_count' => $this->when(!TokenFactory::isAdmin(), TokenFactory::getCurrentUser()->orders()->unpaid()->count()),
            'paid_count' => $this->when(!TokenFactory::isAdmin(), TokenFactory::getCurrentUser()->orders()->paid()->count()),
            'undelivered_count' => $this->when(!TokenFactory::isAdmin(), TokenFactory::getCurrentUser()->orders()->undelivered()->count()),
            'delivered_count' => $this->when(!TokenFactory::isAdmin(), TokenFactory::getCurrentUser()->orders()->delivered()->count()),
            'received_count' => $this->when(!TokenFactory::isAdmin(), TokenFactory::getCurrentUser()->orders()->received()->count()),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}