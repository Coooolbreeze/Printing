<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/21
 * Time: 15:37
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends ApiController
{
    public function index()
    {
        return $this->success(BannerResource::collection(Banner::all()));
    }

    public function update(Request $request, Banner $banner)
    {
        Banner::updateField($request, $banner, ['image_id', 'url']);
        return $this->message('更新成功');
    }
}