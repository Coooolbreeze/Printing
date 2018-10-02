<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/7
 * Time: 11:43
 */

namespace App\Http\Controllers\Api;


use App\Exports\FinanceExport;
use App\Http\Resources\FinanceStatisticCollection;
use App\Models\FinanceStatistic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FinanceStatisticController extends ApiController
{
    public function index(Request $request)
    {
        $financeStatistic = (new FinanceStatistic())
            ->when($request->begin_time, function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $request->end_time))
                ]);
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->latest()
            ->paginate(FinanceStatistic::getLimit());

        return $this->success(new FinanceStatisticCollection($financeStatistic));
    }

    public function statistic()
    {
        $financeStatistic = FinanceStatistic::all();

        $totalIncome = $financeStatistic->where('type', 1)->sum('price');
        $totalRefund = $financeStatistic->where('type', 2)->sum('price');

        $totalIncome = ceil($totalIncome * 100) / 100;
        $totalRefund = ceil($totalRefund * 100) / 100;

        return $this->success([
            'total_income' => $totalIncome,
            'income_count' => ceil(($totalIncome - $totalRefund) * 100) / 100,
            'total_refund' => $totalRefund,
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new FinanceExport($request), 'finances.xlsx');
    }
}