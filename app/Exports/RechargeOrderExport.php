<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/10
 * Time: 9:50
 */

namespace App\Exports;


use App\Enum\OrderPayTypeEnum;
use App\Models\RechargeOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RechargeOrderExport implements FromQuery, WithHeadings, WithMapping
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return RechargeOrder::query()
            ->when($this->request->nickname, function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->Where('nickname', 'like', '%' . $this->request->nickname . '%');
                });
            })
            ->when($this->request->begin_time, function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $this->request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $this->request->end_time))
                ]);
            })
            ->latest()
            ->select('created_at', 'order_no', 'price', 'user_id', 'pay_type');
    }

    public function map($row): array
    {
        return [
            (string)$row->created_at,
            $row->order_no,
            $row->price . "\t",
            (User::find($row->user_id))->nickname,
            $row->pay_type == OrderPayTypeEnum::ALI_PAY ? '支付宝' : '微信支付',
        ];
    }

    public function headings(): array
    {
        return [
            '时间',
            '订单号',
            '金额',
            '用户',
            '支付方式'
        ];
    }
}