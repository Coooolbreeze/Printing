<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/7
 * Time: 13:16
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\EntityResource;
use App\Http\Resources\UserResource;
use App\Models\Entity;
use App\Models\FinanceStatistic;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class OperateStatisticController extends ApiController
{
    public function today()
    {
        $today = FinanceStatistic::whereBetween('created_at', [
            Carbon::today(),
            Carbon::now()
        ])->get();
        $todayIncome = $today->where('type', 1)->sum('price');
        $todayRefund = $today->where('type', 2)->sum('price');
        $todayTotal = $todayIncome - $todayRefund;

        $yesterday = FinanceStatistic::whereBetween('created_at', [
            Carbon::yesterday(),
            Carbon::today()
        ])->get();
        $yesterdayIncome = $yesterday->where('type', 1)->sum('price');
        $yesterdayRefund = $yesterday->where('type', 2)->sum('price');
        $yesterdayTotal = $yesterdayIncome - $yesterdayRefund;

        $dayOnDayRatio = $todayTotal - $yesterdayTotal / abs($yesterdayTotal);

        $orderCount = Order::whereBetween('created_at', [
            Carbon::today(),
            Carbon::now()
        ])->count();

        $paidCount = $today->where('type', 1)->count();

        $newUser = User::whereBetween('created_at', [
            Carbon::today(),
            Carbon::now()
        ])->count();

        return $this->success([
            'total_income' => $todayTotal,
            'day_on_day_ratio' => $dayOnDayRatio,
            'order_count' => $orderCount,
            'paid_count' => $paidCount,
            'new_user' => $newUser
        ]);
    }

    public function goodsRank()
    {
        $goods = Entity::orderBy('sales', 'desc')
            ->limit(7)
            ->get();

        return $this->success(EntityResource::collection($goods)->show(['name', 'sales']));
    }

    public function userRank()
    {
        $users = User::orderBy('consume', 'desc')
            ->limit(7)
            ->get();

        return $this->success(UserResource::collection($users)->show(['nickname', 'consume']));
    }
}