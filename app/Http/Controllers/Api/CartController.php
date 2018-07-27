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
    private static function checkCartsInfo($carts)
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
     * @param $cart
     * @return array
     * @throws \App\Exceptions\TokenException
     */
    private static function getSaveInfo($cart)
    {
        $customSpecs = array_key_exists('custom_specs', $cart) ? $cart['custom_specs'] : [];
        $count = array_key_exists('count', $cart) ? $cart['count'] : 0;

        return [
            'user_id' => TokenFactory::getCurrentUID(),
            'entity_id' => $cart['entity_id'],
            'combination_id' => $cart['combination_id'],
            'specs' => json_encode($cart['specs']),
            'custom_specs' => json_encode($customSpecs),
            'file_id' => array_key_exists('file_id', $cart) ? $cart['file_id'] : 0,
            'price' => $cart['price'],
            'weight' => self::getWeight($cart['combination_id'], $customSpecs, $count),
            'count' => $count,
            'remark' => array_key_exists('remark', $cart) ? $cart['remark'] : null
        ];
    }

    private static function getWeight($combinationId, $customSpecs, $count = 0)
    {
        $specWeight = Combination::find($combinationId)->weight;

        foreach ($customSpecs as $customSpec)
            foreach ($customSpec as $value)
                $specWeight *= $value;

        if ($count != 0) $specWeight *= $count;

        return $specWeight;
    }
}