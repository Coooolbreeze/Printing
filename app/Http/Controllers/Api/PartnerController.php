<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/12
 * Time: 10:29
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\PartnerCollection;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PartnerController extends ApiController
{
    public function index()
    {
        return $this->success(new PartnerCollection(Partner::pagination()));
    }

    public function show(Partner $partner)
    {
        return $this->success(new PartnerResource($partner));
    }

    public function store(Request $request)
    {
        Partner::create([
            'image_id' => $request->image_id,
            'url' => $request->url
        ]);
        return $this->created();
    }

    public function update(Request $request, Partner $partner)
    {
        isset($request->image_id) && $partner->image_id = $request->image_id;
        isset($request->url) && $partner->url = $request->url;
        $partner->save();

        return $this->message('更新成功');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return $this->message('删除成功');
    }
}