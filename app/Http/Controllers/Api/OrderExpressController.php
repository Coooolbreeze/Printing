<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/31
 * Time: 18:07
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderStatusEnum;
use App\Events\OrderDelivered;
use App\Http\Requests\StoreOrderExpress;
use App\Http\Resources\OrderExpressResource;
use App\Models\Order;
use App\Models\OrderExpress;
use Illuminate\Http\Request;

class OrderExpressController extends ApiController
{
    public function index(Request $request)
    {
        return $this->success(
            OrderExpressResource::collection(
                OrderExpress::where('order_id', $request->order_id)->get()
            )
        );
    }

    /**
     * @param StoreOrderExpress $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(StoreOrderExpress $request)
    {
        \DB::transaction(function () use ($request) {
            OrderExpress::create([
                'order_id' => $request->order_id,
                'company' => $request->company,
                'code' => $request->code,
                'tracking_no' => $request->tracking_no
            ]);

            $order = Order::findOrFail($request->order_id);

            if ($order->status == OrderStatusEnum::UNDELIVERED) {
                $order->update([
                    'status' => OrderStatusEnum::DELIVERED
                ]);
                event(new OrderDelivered($order));
            }
        });

        return $this->created();
    }
}