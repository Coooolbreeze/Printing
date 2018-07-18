<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/6
 * Time: 16:15
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Requests\StoreCart;
use App\Models\Cart;
use App\Models\Combination;
use App\Services\Tokens\TokenFactory;

class CartController extends ApiController
{
    public function store(StoreCart $request)
    {
        if (!Combination::isSpecMatch($request->combination_id, $request->specs))
            throw new BaseException('商品规格不匹配');
        if (!Combination::isPriceMatch($request->combination_id, $request->price, $request->custom_specs ?: [], $request->count ?: 0))
            throw new BaseException('商品价格不匹配');

        Cart::create([
            'user_id' => TokenFactory::getCurrentUID(),
            'entity_id' => $request->entity_id,
            'combination_id' => $request->combination_id,
            'custom_specs' => json_encode($request->custom_specs ?: []),
            'file_id' => $request->file_id ?: 0,
            'price' => $request->price,
            'count' => $request->count
        ]);

        return $this->created();
    }
}