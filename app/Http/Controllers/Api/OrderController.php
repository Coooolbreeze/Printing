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
use App\Models\Cart;
use App\Models\Combination;
use App\Models\File;
use App\Models\Order;
use App\Services\Tokens\TokenFactory;

class OrderController extends ApiController
{
    /**
     * @param StoreOrder $request
     * @throws \Throwable
     */
    public function store(StoreOrder $request)
    {
        \DB::transaction(function () use ($request) {

        });
    }

    public static function entityOrder($entity)
    {
        Order::create([

        ]);
    }

    /**
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public static function cartOrder($ids)
    {
        $cartsAll = TokenFactory::getCurrentUser()->carts()->pluck('id')->toArray();
        if (array_intersect($ids, $cartsAll) != $ids) throw new BaseException('提交订单中有不存在购物车中的数据');

        $carts = Cart::whereIn('id', $ids)->get();

        $entityArr = [];
        $carts->each(function ($value) use (&$entityArr) {
            $combination = Combination::findOrFail($value->combination_id);

            $remark = '';
            $customSpecs = json_decode($value->custom_specs, true);
            if ($customSpecs) {
                foreach ($customSpecs as $key => $spec) {
                    $remark .= $key . ':';
                    foreach ($spec as $k => $v) {
                        $remark .= $v . '*';
                    }
                    $remark = rtrim($remark,'*');
                    $remark .= ';';
                }
            }
            $value->remark && $remark .= $value->remark;

            $entity = [
                'id' => $value->entity_id,
                'combination' => $combination->combination,
                'weight' => $combination->weight,
                'count' => $value->count ?: 1,
                'price' => $value->price,
                'remark' => $remark
            ];

            ($value->file_id != 0) && $entity['file'] = new FileResource(File::findOrFail($value->file_id));

            array_push($entityArr, $entity);
        });
        return $entityArr;
    }
}