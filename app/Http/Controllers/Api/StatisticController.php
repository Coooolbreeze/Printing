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
use App\Services\Tokens\TokenFactory;
use Carbon\Carbon;

class StatisticController extends ApiController
{
    /**
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function index()
    {
        $todayOrder = Order::whereDate('created_at', Carbon::today()->toDateString())->count();
        $undeliveredOrder = Order::where('status', OrderStatusEnum::UNDELIVERED)->count();
        $auditedOrder = Order::where('status', OrderStatusEnum::PAID)->count();
        $receiptCount = Receipt::where('is_receipted', 0)->count();
        $userCount = User::where('is_admin', 0)->count();
        $newUserCount = User::where('is_admin', 0)
            ->whereBetween('created_at', [
                Carbon::today(),
                Carbon::now()
            ])->count();

        $arr = [];
        $permissions = TokenFactory::getCurrentPermissions();
        if (in_array('订单管理', $permissions)) {
            $arr = array_merge($arr, [
                'today_order' => $todayOrder,
                'undelivered_order' => $undeliveredOrder,
                'audited_order' => $auditedOrder,
            ]);
        }
        if (in_array('财务管理', $permissions)) {
            $arr = array_merge($arr, [
                'receipt' => $receiptCount
            ]);
        }
        if (in_array('用户管理', $permissions)) {
            $arr = array_merge($arr, [
                'user' => $userCount,
                'new_user' => $newUserCount
            ]);
        }

        return $this->success($arr);
    }
}