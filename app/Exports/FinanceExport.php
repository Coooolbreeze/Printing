<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/10
 * Time: 9:41
 */

namespace App\Exports;


use App\Models\FinanceStatistic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinanceExport implements FromQuery, WithHeadings, WithMapping
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return FinanceStatistic::query()
            ->when($this->request->begin_time, function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $this->request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $this->request->end_time))
                ]);
            })
            ->when($this->request->type, function ($query) {
                $query->where('type', $this->request->type);
            })
            ->latest()
            ->select('created_at', 'type', 'order_no', 'price');
    }

    public function map($row): array
    {
        return [
            (string)$row->created_at,
            $row->type == 1 ? '收入' : '支出',
            $row->order_no,
            $row->price . "\t"
        ];
    }

    public function headings(): array
    {
        return [
            '时间',
            '类型',
            '订单号',
            '金额'
        ];
    }
}