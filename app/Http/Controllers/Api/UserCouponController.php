<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 14:58
 */

namespace App\Http\Controllers\Api;


use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Http\Request;

class UserCouponController extends ApiController
{
    /**
     * 领取优惠券
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        Coupon::receive($request->coupon_no);

        return $this->message('领取成功');
    }

    public function record()
    {
        $record = UserCoupon::latest()->limit(4)->get();

        return $this->success([
            'user' => self::partialHidden($record->user->phone, 3, 4),
            'coupon' => $record->name,
            'created_at' => (string)$this->created_at
        ]);
    }

    private static function partialHidden($value, $start, $length)
    {
        if (!$value) {
            return $value;
        }

        return substr_replace($value, '****', $start, $length);
    }
}