<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 11:11
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\SceneGoodResource;
use App\Models\SceneGood;
use Illuminate\Http\Request;

class SceneGoodController extends ApiController
{
    public function show(SceneGood $sceneGood)
    {
        return $this->success(new SceneGoodResource($sceneGood));
    }

    public function store(Request $request)
    {
        SceneGood::create([
            'scene_category_id' => $request->scene_category_id,
            'image_id' => $request->image_id,
            'name' => $request->name,
            'describe' => $request->describe,
            'url' => $request->url
        ]);

        return $this->created();
    }

    public function update(Request $request, SceneGood $sceneGood)
    {
        SceneGood::updateField($request, $sceneGood, ['image_id', 'name', 'describe', 'url']);

        return $this->message('更新成功');
    }

    public function destroy(SceneGood $sceneGood)
    {
        $sceneGood->delete();
        return $this->message('删除成功');
    }
}