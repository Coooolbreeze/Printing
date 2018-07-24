<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/23
 * Time: 16:47
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\GiftCollection;
use App\Http\Resources\GiftResource;
use App\Models\Gift;
use Illuminate\Http\Request;

class GiftController extends ApiController
{
    public function index()
    {
        return $this->success(new GiftCollection(Gift::pagination()));
    }

    public function show(Gift $gift)
    {
        return $this->success(new GiftResource($gift));
    }

    public function store(Request $request)
    {
        Gift::create([
            'name' => $request->name,
            'image_id' => $request->image_id,
            'accumulate_points' => $request->accumulate_points,
            'detail' => $request->detail,
            'stock' => $request->stock
        ]);

        return $this->created();
    }

    public function update(Request $request, Gift $gift)
    {
        isset($request->name) && $gift->name = $request->name;
        isset($request->image_id) && $gift->image_id = $request->image_id;
        isset($request->accumulate_points) && $gift->accumulate_points = $request->accumulate_points;
        isset($request->detail) && $gift->detail = $request->detail;
        isset($request->stock) && $gift->stock = $request->stock;
        $gift->save();

        return $this->message('更新成功');
    }

    /**
     * @param Gift $gift
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Gift $gift)
    {
        $gift->delete();
        return $this->message('删除成功');
    }
}