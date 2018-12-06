<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/11/11
 * Time: 20:18
 */

namespace App\Http\Controllers\Api;


use App\Models\Order;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class ConstructionBillController extends ApiController
{
    public function index(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $goods = [];
        $content = json_decode($order->snap_content, true);
        foreach ($content as $good) {
            array_push($goods, [
                'image' => $good['image']['src'],
                'describe' => $good['name'] . ',' . implode(',', explode('|', $good['combination']['name'])) . ',' . self::joinSpecs($good['custom_specs']),
                'type' => $good['type'],
                'count' => $good['count']
            ]);
        }

        return $this->success([
            'order_no' => $order->order_no,
            'created_at' => (string)$order->created_at,
            'goods' => $goods,
            'bill_remark' => $order->bill_remark,
            'clerk' => TokenFactory::getCurrentUser()->nickname
        ]);
    }

    private static function joinSpecs($specs)
    {
        $str = '';
        foreach ($specs as $key => $spec) {
            $str .= $key . '：';
            foreach ($spec as $k => $v) $str .= $k . $v . 'CM*';
            $str = rtrim($str, '*') . ';';
        }
        return $str;
    }
}