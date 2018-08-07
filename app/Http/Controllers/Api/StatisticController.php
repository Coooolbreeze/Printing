<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/7
 * Time: 14:48
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderStatusEnum;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\User;
use Carbon\Carbon;

class StatisticController extends ApiController
{
    public function index()
    {
        $todayOrder = Order::whereDate('created_at', Carbon::today()->toDateString())->count();
        $undeliveredOrder = Order::where('status', OrderStatusEnum::UNDELIVERED)->count();
        $auditedOrder = Order::where('status', OrderStatusEnum::PAID)->count();
        $receiptCount = Receipt::where('is_receipted', 0)->count();
        $userCount = User::where('is_admin', 0)->count();
        $newUserCount = User::where('is_admin', 0)
            ->whereDate('created_at', Carbon::today()->toDateString())->count();

        return $this->success([
            'today_order' => $todayOrder,
            'undelivered_order' => $undeliveredOrder,
            'audited_order' => $auditedOrder,
            'receipt' => $receiptCount,
            'user' => $userCount,
            'new_user' => $newUserCount
        ]);
    }
}