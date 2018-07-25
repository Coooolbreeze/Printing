<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\AccumulatePointsRecordCollection;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\GiftOrderCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\AccumulatePointsRecord;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\GiftOrder;
use App\Models\User;
use App\Services\Tokens\TokenFactory;
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
    public function index()
    {
        return $this->success(new UserCollection(User::pagination()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success(new UserResource(User::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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

    public function coupons()
    {
        return $this->success(
            new CouponCollection(TokenFactory::getCurrentUser()->coupons()->paginate(Coupon::getLimit()))
        );
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
            ->orderBy('created_at', 'desc')
            ->paginate(GiftOrder::getLimit())
        ));
    }
}
