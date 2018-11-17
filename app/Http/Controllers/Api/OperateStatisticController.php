<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/7
 * Time: 13:16
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderStatusEnum;
use App\Http\Resources\EntityResource;
use App\Http\Resources\UserResource;
use App\Models\Entity;
use App\Models\FinanceStatistic;
use App\Models\Order;
use App\Models\TypeSale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $dayOnDayRatio = $yesterdayTotal === 0 ? 0 : ($todayTotal - $yesterdayTotal / abs($yesterdayTotal));

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

    public function typePercent()
    {
        $total = TypeSale::sum('sales_volume');

        $top5 = TypeSale::orderBy('sales_volume', 'desc')
            ->limit(5)
            ->get();

        $arr = [];
        $top5->each(function ($type) use (&$arr, $total) {
            array_push($arr, [
                'name' => $type->type,
                'sales_volume' => $type->sales_volume,
                'percent' => round($type->sales_volume / $total * 100) . '%'
            ]);
        });

        if ($top5->sum('sales_volume') != $total) {
            array_push($arr, [
                'name' => '其他',
                'sales_volume' => (string)(round(($total - $top5->sum('sales_volume')) * 100) / 100),
                'percent' => round(($total - $top5->sum('sales_volume')) / $total * 100) . '%'
            ]);
        }

        return $this->success([
            'total_sales_volume' => $total,
            'type_percent' => $arr
        ]);
    }

    public function statistics(Request $request)
    {
        if ($request->year) {
            if ($request->orders) {
                $stats = self::getYearOrders();
            } else {
                $stats = self::getYearSalesVolume();
            }
        } else {
            if ($request->orders) {
                $stats = self::getMonthOrders($request);
            }else {
                $stats = self::getMonthSalesVolume($request);
            }
        }

        return $stats;
    }

    public static function getMonthSalesVolume($request)
    {
        $begin = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth()->addDay();

        if ($request->begin_time && $request->end_time) {
            $begin = Carbon::parse(date('Y-m-d H:i:s', $request->begin_time));
            $end = Carbon::parse(date('Y-m-d H:i:s', $request->end_time))->addDay();
        }

        $dValue = ceil(($end->timestamp - $begin->timestamp) / 86400) - 1;

        $stats = \DB::table(
            \DB::raw('(SELECT @cdate := date_add(@cdate,interval -1 day) date from (SELECT @cdate := DATE("' . $end . '") from statistic_samples limit ' . $dValue . ') t1) t2')
        )
            ->leftJoin('orders', function ($join) {
                $join->on(\DB::raw('DATE(orders.created_at)'), '=', 't2.date')
                    ->whereNotIn('orders.status', [
                        OrderStatusEnum::EXPIRE,
                        OrderStatusEnum::UNPAID,
                        OrderStatusEnum::FAILED,
                        OrderStatusEnum::REFUNDED
                    ]);
            })
            ->groupBy('t2.date')
            ->orderBy('t2.date')
            ->get([
                \DB::raw('t2.date'),
                \DB::raw('SUM(orders.total_price) as sales_volume')
            ])->each(function (&$stat) {
                if ($stat->sales_volume == null) {
                    $stat->sales_volume = '0';
                }
            });

        return $stats;
    }

    public static function getMonthOrders($request)
    {
        $begin = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth()->addDay();

        if ($request->begin_time && $request->end_time) {
            $begin = Carbon::parse(date('Y-m-d H:i:s', $request->begin_time));
            $end = Carbon::parse(date('Y-m-d H:i:s', $request->end_time))->addDay();
        }

        $dValue = ceil(($end->timestamp - $begin->timestamp) / 86400) - 1;

        $stats = \DB::table(
            \DB::raw('(SELECT @cdate := date_add(@cdate,interval -1 day) date from (SELECT @cdate := DATE("' . $end . '") from statistic_samples limit ' . $dValue . ') t1) t2')
        )
            ->leftJoin('orders', function ($join) {
                $join->on(\DB::raw('DATE(orders.created_at)'), '=', 't2.date')
                    ->whereNotIn('orders.status', [
                        OrderStatusEnum::EXPIRE,
                        OrderStatusEnum::UNPAID,
                        OrderStatusEnum::FAILED,
                        OrderStatusEnum::REFUNDED
                    ]);
            })
            ->groupBy('t2.date')
            ->orderBy('t2.date')
            ->get([
                \DB::raw('t2.date'),
                \DB::raw('COUNT(orders.total_price) as count')
            ]);

        return $stats;
    }

    public static function getYearSalesVolume()
    {
        $begin = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear()->addDay();

        $dValue = ceil(($end->timestamp - $begin->timestamp) / 86400) - 1;

        $stats = \DB::table(
            \DB::raw('(SELECT @cdate := date_add(@cdate,interval -1 day) date from (SELECT @cdate := DATE("' . $end . '") from statistic_samples limit ' . $dValue . ') t1) t2')
        )
            ->leftJoin('orders', function ($join) {
                $join->on(\DB::raw('DATE(orders.created_at)'), '=', 't2.date')
                    ->whereNotIn('orders.status', [
                        OrderStatusEnum::EXPIRE,
                        OrderStatusEnum::UNPAID,
                        OrderStatusEnum::FAILED,
                        OrderStatusEnum::REFUNDED
                    ]);
            })
            ->groupBy(\DB::raw('DATE_FORMAT(t2.date, "%Y-%m")'))
            ->orderBy('t2.date')
            ->get([
                \DB::raw('SUM(orders.total_price) as sales_volume')
            ])
            ->each(function (&$stat, $key) {
                if ($stat->sales_volume == null) {
                    $stat->sales_volume = '0';
                }
                $stat->month = $key + 1 . '月';
            });

        return $stats;
    }

    public static function getYearOrders()
    {
        $begin = Carbon::now()->startOfYear();
        $end = Carbon::now()->endOfYear()->addDay();

        $dValue = ceil(($end->timestamp - $begin->timestamp) / 86400) - 1;

        $stats = \DB::table(
            \DB::raw('(SELECT @cdate := date_add(@cdate,interval -1 day) date from (SELECT @cdate := DATE("' . $end . '") from statistic_samples limit ' . $dValue . ') t1) t2')
        )
            ->leftJoin('orders', function ($join) {
                $join->on(\DB::raw('DATE(orders.created_at)'), '=', 't2.date')
                    ->whereNotIn('orders.status', [
                        OrderStatusEnum::EXPIRE,
                        OrderStatusEnum::UNPAID,
                        OrderStatusEnum::FAILED,
                        OrderStatusEnum::REFUNDED
                    ]);
            })
            ->groupBy(\DB::raw('DATE_FORMAT(t2.date, "%Y-%m")'))
            ->orderBy('t2.date')
            ->get([
                \DB::raw('COUNT(orders.total_price) as count')
            ])
            ->each(function (&$stat, $key) {
                $stat->month = $key + 1 . '月';
            });

        return $stats;
    }
}