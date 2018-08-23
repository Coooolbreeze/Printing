<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 11:08
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\SceneCategoryCollection;
use App\Http\Resources\SceneGoodCollection;
use App\Models\SceneCategory;
use App\Models\SceneGood;
use Illuminate\Http\Request;

class SceneCategoryController extends ApiController
{
    public function index(Request $request)
    {
        return $this->success(
            new SceneCategoryCollection(
                SceneCategory::where('scene_id', $request->scene_id)->paginate(SceneCategory::getLimit())
            )
        );
    }

    public function show(SceneCategory $sceneCategory)
    {
        return $this->success(
            new SceneGoodCollection($sceneCategory->sceneGoods()->paginate(SceneGood::getLimit()))
        );
    }

    public function update(Request $request, SceneCategory $sceneCategory)
    {
        $sceneCategory->update([
            'name' => $request->name
        ]);
        return $this->message('更新成功');
    }

    public function destroy($id)
    {
        \DB::transaction(function () use ($id) {
            SceneCategory::destroy($id);
            SceneGood::where('scene_category_id', $id)->delete();
        });
        return $this->message('删除成功');
    }
}