<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/6
 * Time: 16:54
 */

namespace App\Exports;


use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LogExport implements FromQuery, WithHeadings, WithMapping
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Log::query()
            ->when($this->request->begin_time, function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $this->request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $this->request->end_time))
                ]);
            })
            ->latest()
            ->select('id', 'created_at', 'url', 'action', 'user', 'ip');
    }

    public function map($row): array
    {
        return [
            $row->id . "\t",
            (string)$row->created_at,
            $row->url,
            $row->action,
            $row->user,
            $row->ip . "\t"
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            '操作时间',
            '访问地址',
            '操作项',
            '用户名',
            '用户IP'
        ];
    }
}