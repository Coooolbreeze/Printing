<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/14
 * Time: 17:51
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Requests\StoreCoupon;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;
use App\Services\Tokens\TokenFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends ApiController
{
    public function index()
    {
        return $this->success(new CouponCollection(
            Coupon::when(!TokenFactory::isAdmin(), function ($query) {
                $query->where('is_disabled', 0);
            })->paginate(Coupon::getLimit())
        ));
    }

    public function show(Coupon $coupon)
    {
        return $this->success(new CouponResource($coupon));
    }

    public function store(StoreCoupon $request)
    {
        Coupon::create([
            'coupon_no' => uuid(),
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

    /**
     * 赠送优惠券
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    public function give(Request $request)
    {
        $user = User::where('phone', $request->phone)
            ->firstOrFail();

        Coupon::receive($request->coupon_no, $user);

        return $this->message('赠送成功');
    }

    /**
     * @param Request $request
     * @param Coupon $coupon
     * @return mixed
     * @throws \Throwable
     */
    public function update(Request $request, Coupon $coupon)
    {
        \DB::transaction(function () use ($request, $coupon) {
            isset($request->finished_at) && $coupon->finished_at = Carbon::parse(date('Y-m-d H:i:s', $request->finished_at));
            Coupon::updateField($request, $coupon, ['name', 'type', 'quota', 'satisfy', 'number', 'is_meanwhile', 'is_disabled']);

            if (isset($request->is_disabled)) {
                UserCoupon::where('coupon_id', $coupon->id)
                    ->update([
                        'is_disabled' => $request->is_disabled
                    ]);
            }
        });

        return $this->message('更新成功');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return $this->message('删除成功');
    }
}