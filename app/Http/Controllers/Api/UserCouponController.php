<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 14:58
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class UserCouponController extends ApiController
{
    /**
     * 领取优惠券
     *
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        \DB::transaction(function () use ($request) {
            $coupon = Coupon::where('coupon_no', $request->coupon_no)
                ->lockForUpdate()
                ->firstOrFail();

            $received = TokenFactory::getCurrentUser()->receivedCoupons()->pluck('id')->toArray();
            if (in_array($coupon->id, $received)) throw new BaseException('已经领取，不可重复领取');
            if ($coupon->received >= $coupon->number) throw new BaseException('该优惠券已被领完');

            UserCoupon::create([
                'user_id' => TokenFactory::getCurrentUID(),
                'coupon_no' => $coupon->coupon_no,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'quota' => $coupon->quota,
                'satisfy' => $coupon->satisfy,
                'is_meanwhile' => $coupon->is_meanwhile,
                'finished_at' => $coupon->finished_at
            ]);

            TokenFactory::getCurrentUser()->receivedCoupons()->attach($coupon->id);
            $coupon->increment('received', 1);
        });

        return $this->message('领取成功');
    }
}