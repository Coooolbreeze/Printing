<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/8
 * Time: 11:26
 */

namespace App\Http\Controllers\Api;


use App\Exports\RechargeOrderExport;
use App\Http\Resources\RechargeOrderCollection;
use App\Http\Resources\RechargeOrderResource;
use App\Models\RechargeOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RechargeOrderController extends ApiController
{
    public function index(Request $request)
    {
        $rechargeOrder = (new RechargeOrder())
            ->when($request->nickname, function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->Where('nickname', 'like', '%' . $request->nickname . '%');
                });
            })
            ->when($request->begin_time, function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $request->end_time))
                ]);
            })
            ->latest()
            ->paginate(RechargeOrder::getLimit());

        return $this->success(new RechargeOrderCollection($rechargeOrder));
    }

    public function show($id)
    {
        $rechargeOrder = RechargeOrder::find($id);

        if ($rechargeOrder && $rechargeOrder->is_paid == 1) {
            $result = true;
        } else {
            $result = false;
        }

        return $this->success([
            'is_paid' => $result
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new RechargeOrderExport($request), 'recharge_orders.xlsx');
    }
}