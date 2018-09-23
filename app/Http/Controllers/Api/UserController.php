<?php

namespace App\Http\Controllers\Api;


use App\Enum\OrderStatusEnum;
use App\Exceptions\BaseException;
use App\Http\Requests\UpdateUser;
use App\Http\Resources\AccumulatePointsRecordCollection;
use App\Http\Resources\AddressResource;
use App\Http\Resources\BalanceRecordCollection;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\EntityCollection;
use App\Http\Resources\GiftOrderCollection;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\ReceiptCollection;
use App\Http\Resources\RechargeOrderCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserCouponCollection;
use App\Http\Resources\UserResource;
use App\Models\AccumulatePointsRecord;
use App\Models\BalanceRecord;
use App\Models\Cart;
use App\Models\Comment;
use App\Models\Coupon;
use App\Models\Entity;
use App\Models\GiftOrder;
use App\Models\Message;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\RechargeOrder;
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

    public function all(Request $request)
    {
        $user = (new User())
            ->where('is_admin', 0)
            ->when($request->value, function ($query) use ($request) {
                $query->where('nickname', 'like', '%' . $request->value . '%')
                    ->orWhere('phone', 'like', '%' . $request->value . '%');
            })
            ->get();

        return $this->success(UserResource::collection($user)->show(['id', 'nickname', 'phone']));
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
        // 添加积分
        if ($request->accumulate_points && $request->accumulate_points > $user->accumulate_points) {
            AccumulatePointsRecord::income(
                $request->accumulate_points - $user->accumulate_points,
                '管理员' . TokenFactory::getCurrentUser()->nickname . '添加',
                $user
            );
        } // 扣除积分
        elseif ($request->accumulate_points && $request->accumulate_points < $user->accumulate_points) {
            AccumulatePointsRecord::expend(
                $user->accumulate_points - $request->accumulate_points,
                '管理员' . TokenFactory::getCurrentUser()->nickname . '扣除',
                $user
            );
        }
        // 添加余额
        if ($request->balance && $request->balance > $user->balance) {
            BalanceRecord::income(
                $request->balance - $user->balance,
                '管理员' . TokenFactory::getCurrentUser()->nickname . '添加',
                $user
            );
        } // 扣除余额
        elseif ($request->balance && $request->balance < $user->balance) {
            BalanceRecord::expend(
                $user->balance - $request->balance,
                '管理员' . TokenFactory::getCurrentUser()->nickname . '扣除',
                $user
            );
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
     * 更新自己的资料
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function selfUpdate(Request $request)
    {
        User::updateField($request, TokenFactory::getCurrentUser(), ['nickname', 'sex', 'avatar']);

        return $this->message('更新成功');
    }

    /**
     * @return string
     * @throws \App\Exceptions\TokenException
     */
    public function roles()
    {
        return $this->success(TokenFactory::getCurrentRoles());
    }

    /**
     * 获取拥有的权限
     *
     * @return array
     * @throws \App\Exceptions\TokenException
     */
    public function permissions()
    {
        return $this->success(TokenFactory::getCurrentPermissions());
    }

    /**
     * 我的优惠券
     *
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

    /**
     * 我的积分记录
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
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

    /**
     * 我的资产记录
     *
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function balanceRecords(Request $request)
    {
        return $this->success(
            new BalanceRecordCollection(TokenFactory::getCurrentUser()
                ->balanceRecords()
                ->when($request->type, function ($query) use ($request) {
                    $query->where('type', $request->type);
                })
                ->latest()
                ->paginate(BalanceRecord::getLimit())
            )
        );
    }

    /**
     * 我的充值订单
     *
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function rechargeOrders()
    {
        return $this->success(
            new RechargeOrderCollection(TokenFactory::getCurrentUser()
                ->rechargeOrders()
                ->latest()
                ->paginate(RechargeOrder::getLimit())
            )
        );
    }

    /**
     * 我的收货地址
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
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

    /**
     * 我的礼品订单
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
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

    /**
     * 我的购物车
     *
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function carts()
    {
        return $this->success(new CartCollection(TokenFactory::getCurrentUser()
            ->carts()
            ->latest()
            ->paginate(Cart::getLimit())
        ));
    }

    /**
     * 我的订单
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function orders(Request $request)
    {
        return $this->success(new OrderCollection(TokenFactory::getCurrentUser()
            ->orders()
            ->when($request->unreceipt, function ($query) {
                $query->whereNull('receipt_id')
                    ->whereIn('status', [
                        OrderStatusEnum::RECEIVED,
                        OrderStatusEnum::COMMENTED
                    ]);
            })
            ->when(isset($request->status), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(Order::getLimit())
        ));
    }

    /**
     * 我的发票
     *
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function receipts()
    {
        return $this->success(new ReceiptCollection(TokenFactory::getCurrentUser()
            ->receipts()
            ->latest()
            ->paginate(Receipt::getLimit())
        ));
    }

    /**
     * 我的评价
     *
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function comments()
    {
        return $this->success(new CommentCollection(TokenFactory::getCurrentUser()
            ->comments()
            ->latest()
            ->paginate(Comment::getLimit())
        ));
    }

    public function follows()
    {
        return $this->success(new EntityCollection(TokenFactory::getCurrentUser()
            ->entities()
            ->latest()
            ->paginate(Entity::getLimit())
        ));
    }

    /**
     * 我的消息
     *
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function messages(Request $request)
    {
        return $this->success(new MessageCollection(TokenFactory::getCurrentUser()
            ->messages()
            ->when(isset($request->is_read), function ($query) use ($request) {
                $query->where('is_read', $request->is_read);
            })
            ->orderBy('is_read', 'asc')
            ->latest()
            ->paginate(Message::getLimit())
        ));
    }

    /**
     * 我的未读消息数
     *
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function unreadMessageCount()
    {
        return $this->success([
            'count' => TokenFactory::getCurrentUser()
                ->messages()
                ->where('is_read', 0)
                ->count()
        ]);
    }
}
