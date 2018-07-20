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
use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Models\Combination;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    /**
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function index()
    {
        return $this->success(
            new CartCollection(
                TokenFactory::getCurrentUser()->carts()->paginate(Cart::getLimit())
            )
        );
    }

    /**
     * @param StoreCart $request
     * @return mixed
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public function store(StoreCart $request)
    {
        self::checkCartsInfo($request->toArray());

        Cart::create(self::getSaveInfo($request->toArray()));

        return $this->created();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public function batchStore(Request $request)
    {
        $carts = [];
        foreach ($request->carts as $cart) {
            self::checkCartsInfo($cart);

            array_push($carts, self::getSaveInfo($cart));
        }
        Cart::saveAll($carts);

        return $this->created();
    }

    /**
     * @param Cart $cart
     * @throws \Exception
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
        $this->message('删除成功');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function batchDestroy(Request $request)
    {
        Cart::whereIn('id', $request->ids)
            ->delete();
        return $this->message('删除成功');
    }

    /**
     * @param $carts
     * @throws BaseException
     */
    public static function checkCartsInfo($carts)
    {
        if (!Combination::isEntityMatch($carts['combination_id'], $carts['entity_id']))
            throw new BaseException('商品组合不匹配');

        if (!Combination::isSpecMatch($carts['combination_id'], $carts['specs']))
            throw new BaseException('商品规格不匹配');

        if (!Combination::isPriceMatch(
            $carts['combination_id'],
            $carts['price'],
            array_key_exists('custom_specs', $carts) ? $carts['custom_specs'] : [],
            array_key_exists('count', $carts) ? $carts['count'] : 0)
        ) throw new BaseException('商品价格不匹配');
    }

    /**
     * @param $carts
     * @return array
     * @throws \App\Exceptions\TokenException
     */
    public function getSaveInfo($carts)
    {
        return [
            'user_id' => TokenFactory::getCurrentUID(),
            'entity_id' => $carts['entity_id'],
            'combination_id' => $carts['combination_id'],
            'specs' => json_encode($carts['specs']),
            'custom_specs' => json_encode(array_key_exists('custom_specs', $carts) ? $carts['custom_specs'] : []),
            'file_id' => array_key_exists('file_id', $carts) ? $carts['file_id'] : 0,
            'price' => $carts['price'],
            'count' => array_key_exists('count', $carts) ? $carts['count'] : 0,
            'remark' => array_key_exists('remark', $carts) ? $carts['remark'] : null
        ];
    }
}