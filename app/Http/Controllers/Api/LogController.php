<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/6
 * Time: 15:55
 */

namespace App\Http\Controllers\Api;


use App\Exports\LogExport;
use App\Http\Resources\LogCollection;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LogController extends ApiController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $logs = (new Log())
            ->when($request->begin_time, function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $request->end_time))
                ]);
            })
            ->latest()
            ->paginate(Log::getLimit());

        return $this->success(new LogCollection($logs));
    }

    public function export(Request $request)
    {
        return Excel::download(new LogExport($request), 'logs.xlsx');
    }

    /**
     * @param Log $log
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Log $log)
    {
        $log->delete();
        return $this->message('删除成功');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function batchDestroy(Request $request)
    {
        Log::whereIn('id', $request->ids)
            ->delete();
        return $this->message('删除成功');
    }
}