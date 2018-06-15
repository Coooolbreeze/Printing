<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/14
 * Time: 17:51
 */

namespace App\Http\Controllers\Api;


use App\Http\Requests\StoreCoupon;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends ApiController
{
    public function index()
    {
        return $this->success(new CouponCollection(Coupon::pagination()));
    }

    public function show(Coupon $coupon)
    {
        return $this->success(new CouponResource($coupon));
    }

    public function store(StoreCoupon $request)
    {
        Coupon::create([
            'name' => $request->name,
            'type' => $request->type,
            'quota' => $request->quota,
            'satisfy' => $request->satisfy,
            'number' => $request->number,
            'is_meanwhile' => $request->is_meanwhile,
            'finished_at' => Carbon::parse(date('Y-m-d H:i:s', $request->finished_at))
        ]);
        return $this->created();
    }

    public function update(Request $request, Coupon $coupon)
    {
        isset($request->name) && $coupon->name = $request->name;
        isset($request->type) && $coupon->type = $request->type;
        isset($request->quota) && $coupon->quota = $request->quota;
        isset($request->satisfy) && $coupon->satisfy = $request->satisfy;
        isset($request->number) && $coupon->number = $request->number;
        isset($request->is_meanwhile) && $coupon->is_meanwhile = $request->is_meanwhile;
        isset($request->finished_at) && $coupon->finished_at = Carbon::parse(date('Y-m-d H:i:s', $request->finished_at));
        $coupon->save();

        return $this->message('更新成功');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return $this->message('删除成功');
    }
}