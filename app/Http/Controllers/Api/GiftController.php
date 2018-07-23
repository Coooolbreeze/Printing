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
            'detail' => $request->accumulate_points,
            'stock' => $request->stock
        ]);
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}