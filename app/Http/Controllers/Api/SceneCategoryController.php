<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 11:08
 */

namespace App\Http\Controllers\Api;


use App\Models\SceneCategory;
use App\Models\SceneGood;
use Illuminate\Http\Request;

class SceneCategoryController extends ApiController
{
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