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
        $orders = TokenFactory::getCurrentUser()->orders();

        return [
            'data' => OrderResource::collection($this->collection)->hide(['logs']),
            'unpaid_count' => $this->when(!TokenFactory::isAdmin(), $orders->unpaid()->count()),
            'paid_count' => $this->when(!TokenFactory::isAdmin(), $orders->paid()->count()),
            'undelivered_count' => $this->when(!TokenFactory::isAdmin(), $orders->undelivered()->count()),
            'delivered_count' => $this->when(!TokenFactory::isAdmin(), $orders->delivered()->count()),
            'received_count' => $this->when(!TokenFactory::isAdmin(), $orders->received()->count()),
            'count' => $this->count(),
            'total' => $this->total(),
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'has_more_pages' => $this->hasMorePages()
        ];
    }
}