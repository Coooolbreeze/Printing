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
    public function show(FreeExpress $freeExpress)
    {
        return $this->success(['price' => $freeExpress->price]);
    }

    public function update(Request $request, FreeExpress $freeExpress)
    {
        $freeExpress->update([
            'price' => $request->price
        ]);
        return $this->message('更新成功');
    }
}