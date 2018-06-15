<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 11:11
 */

namespace App\Http\Controllers\Api;


use App\Models\SceneGood;
use Illuminate\Http\Request;

class SceneGoodController extends ApiController
{
    public function update(Request $request, SceneGood $sceneGood)
    {
        isset($request->image_id) && $sceneGood->image_id = $request->image_id;
        isset($request->name) && $sceneGood->name = $request->name;
        isset($request->describe) && $sceneGood->describe = $request->describe;
        isset($request->url) && $sceneGood->url = $request->url;
        $sceneGood->save();

        return $this->message('更新成功');
    }

    public function destroy(SceneGood $sceneGood)
    {
        $sceneGood->delete();
        return $this->message('删除成功');
    }
}