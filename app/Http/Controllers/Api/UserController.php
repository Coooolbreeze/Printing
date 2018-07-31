<?php

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Requests\UpdateUser;
use App\Http\Resources\AccumulatePointsRecordCollection;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\GiftOrderCollection;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\ReceiptCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserCouponCollection;
use App\Http\Resources\UserResource;
use App\Models\AccumulatePointsRecord;
use App\Models\BalanceRecord;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\GiftOrder;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\User;
use App\Services\Tokens\TokenFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * @SWG\GET(
     *     path="/users",
     *     tags={"users"},
     *     summary="获取用户列表",
     *     @SWG\Response(
     *         response=200,
     *         description="successful"
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $user = (new User())
            ->where('is_admin', 0)
            ->when($request->member_level_id, function ($query) use ($request) {
                $query->where('member_level_id', $request->member_level_id);
            })
            ->when($request->nickname, function ($query) use ($request) {
                $query->where('nickname', $request->nickname);
            })
            ->when($request->phone, function ($query) use ($request) {
                $query->where('phone', $request->phone);
            })
            ->paginate(User::getLimit());

        return $this->success(new UserCollection($user));
    }

    public function show($id)
    {
        return $this->success(new UserResource(
            User::where('is_admin', 0)
                ->where('id', $id)
                ->firstOrFail()
        ));
    }

    /**
     * @param UpdateUser $request
     * @param User $user
     * @return mixed
     * @throws \App\Exceptions\BaseException
     * @throws \App\Exceptions\TokenException
     * @throws \Throwable
     */
    public function update(UpdateUser $request, User $user)
    {
        if (TokenFactory::isAdmin()) {
            if ($request->accumulate_points && $request->accumulate_points > $user->accumulate_points) {
                AccumulatePointsRecord::income(
                    $request->accumulate_points - $user->accumulate_points,
                    '管理员' . TokenFactory::getCurrentUser()->nickname . '添加',
                    $user
                );
            } elseif ($request->accumulate_points && $request->accumulate_points < $user->accumulate_points) {
                AccumulatePointsRecord::expend(
                    $user->accumulate_points - $request->accumulate_points,
                    '管理员' . TokenFactory::getCurrentUser()->nickname . '扣除',
                    $user
                );
            }
            if ($request->balance && $request->balance > $user->balance) {
                BalanceRecord::income(
                    $request->balance - $user->balance,
                    '管理员' . TokenFactory::getCurrentUser()->nickname . '添加',
                    $user
                );
            } elseif ($request->balance && $request->balance < $user->balance) {
                BalanceRecord::expend(
                    $user->balance - $request->balance,
                    '管理员' . TokenFactory::getCurrentUser()->nickname . '扣除',
                    $user
                );
            }
        } else {
            User::updateField($request, $user, ['nickname', 'sex', 'avatar']);
        }

        return $this->message('更新成功');
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if ($user->id == 1) throw new BaseException('不能删除超级管理员账号');
        $user->delete();
        return $this->message('删除成功');
    }

    /**
     * 查看自己的资料
     *
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function self()
    {
        return $this->success(new UserResource(TokenFactory::getCurrentUser()));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function coupons(Request $request)
    {
        $coupons = TokenFactory::getCurrentUser()->coupons()
            ->when($request->type == 1, function ($query) use ($request) {
                $query->where('is_used', 0)->whereDate('finished_at', '>', Carbon::now());
            })
            ->when($request->type == 2, function ($query) {
                $query->where('is_used', 1);
            })
            ->when($request->type == 3, function ($query) {
                $query->whereDate('finished_at', '<', Carbon::now());
            })
            ->paginate(Coupon::getLimit());

        return $this->success(new UserCouponCollection($coupons));
    }

    public function accumulatePointsRecords(Request $request)
    {
        return $this->success(
            new AccumulatePointsRecordCollection(TokenFactory::getCurrentUser()
                ->accumulatePointsRecords()
                ->when($request->type, function ($query) use ($request) {
                    $query->where('type', $request->type);
                })
                ->latest()
                ->paginate(AccumulatePointsRecord::getLimit())
            )
        );
    }

    public function addresses(Request $request)
    {
        return $this->success(AddressResource::collection(TokenFactory::getCurrentUser()
            ->addresses()
            ->when($request->is_default, function ($query) {
                $query->where('is_default', 1);
            })
            ->orderBy('is_default', 'desc')
            ->get()
        ));
    }

    public function giftOrders(Request $request)
    {
        return $this->success(new GiftOrderCollection(TokenFactory::getCurrentUser()
            ->giftOrders()
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(GiftOrder::getLimit())
        ));
    }

    public function carts()
    {
        return $this->success(new CartCollection(TokenFactory::getCurrentUser()
            ->carts()
            ->paginate(Cart::getLimit())
        ));
    }

    public function orders(Request $request)
    {
        return $this->success(new OrderCollection(TokenFactory::getCurrentUser()
            ->orders()
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(Order::getLimit())
        ));
    }

    public function receipts()
    {
        return $this->success(new ReceiptCollection(TokenFactory::getCurrentUser()
            ->receipts()
            ->latest()
            ->paginate(Receipt::getLimit())
        ));
    }
}
