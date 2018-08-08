<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/8
 * Time: 11:26
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\RechargeOrderCollection;
use App\Models\RechargeOrder;

class RechargeOrderController extends ApiController
{
    public function index()
    {
        $rechargeOrder = RechargeOrder::latest()->paginate(RechargeOrder::getLimit());

        return $this->success(new RechargeOrderCollection($rechargeOrder));
    }
}