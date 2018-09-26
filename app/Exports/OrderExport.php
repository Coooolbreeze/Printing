<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/10
 * Time: 10:55
 */

namespace App\Exports;


use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromQuery, WithHeadings, WithMapping
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Order::query()
            ->when(isset($this->request->status) && $this->request->status != null, function ($query) {
                $query->where('status', $this->request->status);
            })
            ->when($this->request->begin_time, function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $this->request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $this->request->end_time))
                ]);
            })
            ->latest()
            ->select('order_no', 'title', 'user_id', 'total_price', 'created_at', 'snap_address', 'status');
    }

    public function map($row): array
    {
        return [
            $row->order_no,
            $row->title,
            User::find($row->user_id)->nickname,
            $row->total_price . "\t",
            (string)$row->created_at,
            json_decode($row->snap_address, true)['name'],
            OrderResource::convertStatus($row->status)
        ];
    }

    public function headings(): array
    {
        return [
            '订单编号',
            '商品',
            '用户',
            '应付金额',
            '下单时间',
            '收货人',
            '订单状态'
        ];
    }
}