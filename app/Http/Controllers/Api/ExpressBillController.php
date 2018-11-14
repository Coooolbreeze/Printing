<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/11/15
 * Time: 0:36
 */

namespace App\Http\Controllers\Api;


use App\Models\Order;
use Illuminate\Http\Request;

class ExpressBillController extends ApiController
{
    public function index(Request $request)
    {
        return Order::findOrFail($request->order_id)->express_bill_template;
    }
}