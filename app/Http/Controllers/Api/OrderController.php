<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 16:24
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Requests\StoreOrder;
use App\Http\Resources\FileResource;
use App\Http\Resources\ImageResource;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Combination;
use App\Models\Entity;
use App\Models\Express;
use App\Models\File;
use App\Models\Order;
use App\Models\UserCoupon;
use App\Services\Tokens\TokenFactory;

class OrderController extends ApiController
{
    /**
     * @param StoreOrder $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(StoreOrder $request)
    {
        \DB::transaction(function () use ($request, &$order) {
            $goodsInfo = $request->ids
                ? self::cartOrder($request->ids)
                : self::entityOrder($request->entity);

            $snapAddress = Address::addressSnap($request->address_id);

            $freight = Express::getFreight(
                $request->express_id,
                json_decode($snapAddress, true)['province'],
                $goodsInfo['total_price'],
                $goodsInfo['total_weight']
            );

            $order = [
                'order_no' => 'E-' . makeOrderNo(),
                'user_id' => TokenFactory::getCurrentUID(),
                'goods_count' => $goodsInfo['total_count'],
                'goods_price' => $goodsInfo['total_price'],
                'total_weight' => $goodsInfo['total_weight'],
                'freight' => $freight,
                'snap_address' => $snapAddress,
                'snap_content' => json_encode($goodsInfo['goods'])
            ];

            if ($request->coupon_no) {
                $quota = UserCoupon::use($request->coupon_no, $goodsInfo['total_price']);
                $order['discount_amount'] = $quota;
                $order['coupon_no'] = $request->coupon_no;
            }

            Order::create($order);
        });

        return $this->created();
    }

    private static function entityOrder($entity)
    {

    }

    /**
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    private static function cartOrder($ids)
    {
        $cartsAll = TokenFactory::getCurrentUser()->carts()->pluck('id')->toArray();
        if (array_intersect($ids, $cartsAll) != $ids) throw new BaseException('提交订单中有不存在购物车中的数据');

        $carts = Cart::whereIn('id', $ids)->get();

        $goods = [];
        $totalWeight = 0;
        $totalPrice = 0;
        $totalCount = 0;
        $carts->each(function ($value) use (&$goods, &$totalWeight, &$totalPrice, &$totalCount) {
            $combination = Combination::findOrFail($value->combination_id);

            $entity = [
                'id' => $value->entity_id,
                'image' => new ImageResource(Entity::find($value->entity_id)->images()->first()),
                'combination' => $value->count ? $combination->combination : substr($combination->combination, 0, strripos($combination->combination, '|')),
                'specs' => $value->count ? json_decode($value->specs, true) : array_slice(json_decode($value->specs, true), 0, -1),
                'custom_specs' => json_decode($value->custom_specs, true),
                'weight' => $value->weight,
                'count' => $value->count ?: substr($combination->combination, strripos($combination->combination, '|') + 1),
                'price' => $value->price,
                'remark' => self::joinSpecs(json_decode($value->custom_specs, true)) . $value->remark
            ];

            ($value->file_id != 0) && $entity['file'] = new FileResource(File::findOrFail($value->file_id));

            array_push($goods, $entity);

            $totalWeight += $entity['weight'];
            $totalPrice += $entity['price'];
            $totalCount += self::getCount($entity['count']);
        });

        return [
            'goods' => $goods,
            'total_weight' => $totalWeight,
            'total_price' => $totalPrice,
            'total_count' => $totalCount
        ];
    }

    private static function joinSpecs($specs)
    {
        $str = '';
        foreach ($specs as $key => $spec) {
            $str .= $key . '：';
            foreach ($spec as $k => $v) $str .= $k . $v . '*';
            $str = rtrim($str, '*') . '；';
        }
        return $str;
    }

    private static function getCount($count)
    {
        if (!is_numeric($count)) {
            preg_match_all('/\d+/', $count, $arr);
            $count = $arr[0][0];
        }
        return $count;
    }
}