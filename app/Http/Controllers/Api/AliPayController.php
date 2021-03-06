<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/7
 * Time: 16:51
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderPayTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Events\OrderPaid;
use App\Exceptions\BaseException;
use App\Models\BalanceRecord;
use App\Models\Order;
use App\Models\RechargeOrder;
use App\Services\AliPay;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;
use Yansongda\LaravelPay\Facades\Pay;

class AliPayController extends ApiController
{
    /**
     * @param Request $request
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public function pay(Request $request)
    {
        return (new AliPay())
            ->setOrder(Order::findOrFail($request->order_id))
            ->pay();
    }

    public function recharge(Request $request)
    {
        return (new AliPay())->recharge($request->price);
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function notify()
    {
        return (new AliPay())->notify();
    }
}