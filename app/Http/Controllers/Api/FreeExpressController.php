<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/15
 * Time: 16:42
 */

namespace App\Http\Controllers\Api;


use App\Models\FreeExpress;
use Illuminate\Http\Request;

class FreeExpressController extends ApiController
{
    public function index()
    {
        return $this->success([
            'money' => config('setting.free_express')
        ]);
    }

    public function update(Request $request)
    {
        setEnv([
            'FREE_EXPRESS' => $request->money
        ]);

        return $this->message('更新成功');
    }
}