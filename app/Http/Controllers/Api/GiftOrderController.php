<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/24
 * Time: 18:08
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Requests\StoreGiftOrder;
use App\Http\Requests\UpdateGiftOrder;
use App\Http\Resources\GiftOrderCollection;
use App\Http\Resources\GiftOrderResource;
use App\Http\Resources\ImageResource;
use App\Models\AccumulatePointsRecord;
use App\Models\Address;
use App\Models\Gift;
use App\Models\GiftOrder;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class GiftOrderController extends ApiController
{
    public function index(Request $request)
    {
        $giftOrder = (new GiftOrder())
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->nickname, function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('nickname', $request->nickname);
                });
            })
            ->orderBy('status', 'asc')
            ->latest()
            ->paginate(GiftOrder::getLimit());

        return $this->success(new GiftOrderCollection($giftOrder));
    }

    public function show(GiftOrder $giftOrder)
    {
        return $this->success(new GiftOrderResource($giftOrder));
    }

    /**
     * @param StoreGiftOrder $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(StoreGiftOrder $request)
    {
        \DB::transaction(function () use ($request) {
            $gift = Gift::lockForUpdate()->findOrFail($request->gift_id);

            if ($gift->stock < $request->number) throw new BaseException('库存不足');

            AccumulatePointsRecord::expend($gift->accumulate_points, '兑换礼品');

            GiftOrder::create([
                'order_no' => 'G-' . makeOrderNo(),
                'user_id' => TokenFactory::getCurrentUID(),
                'snap_content' => self::giftSnap($gift, $request->number),
                'snap_address' => Address::addressSnap($request->address_id)
            ]);

            $gift->decrement('stock', $request->number);
        });

        return $this->created();
    }

    public function update(UpdateGiftOrder $request, GiftOrder $giftOrder)
    {
        GiftOrder::updateField($request, $giftOrder, ['status', 'express_company', 'tracking_no']);
        return $this->message('更新成功');
    }

    private static function giftSnap($gift, $number)
    {
        return json_encode([
            'id' => $gift->id,
            'name' => $gift->name,
            'image' => new ImageResource($gift->image),
            'accumulate_points' => $gift->accumulate_points,
            'number' => $number
        ]);
    }
}