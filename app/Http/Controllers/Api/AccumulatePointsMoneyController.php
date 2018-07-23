<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/23
 * Time: 16:48
 */

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

class AccumulatePointsMoneyController extends ApiController
{
    public function index()
    {
        return $this->success([
            'money' => config('setting.accumulate_points_money')
        ]);
    }

    public function update(Request $request)
    {
        setEnv([
            'ACCUMULATE_POINTS_MONEY' => $request->money
        ]);

        return $this->message('更新成功');
    }
}