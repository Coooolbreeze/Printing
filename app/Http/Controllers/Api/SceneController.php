<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 0:16
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\SceneCollection;
use App\Http\Resources\SceneResource;
use App\Models\Scene;
use App\Models\SceneCategory;
use App\Models\SceneGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class SceneController extends ApiController
{
    public function index()
    {
        return $this->success([
            'data' => SceneResource::collection(Scene::all())->show(['id', 'name', 'is_open'])
        ]);
    }

    public function show(Scene $scene)
    {
        return $this->success(new SceneResource($scene));
    }

    public function update(Request $request, Scene $scene)
    {
        Scene::updateField($request, $scene, ['name', 'is_open']);

        return $this->message('更新成功');
    }

    public function store(Request $request)
    {
        $scene = Scene::create([
            'image_id' => $request->image_id,
            'name' => $request->name,
            'describe' => $request->describe
        ]);

        $goods = [];
        foreach ($request->goods as $key => $value) {
            $sceneCategory = SceneCategory::create([
                'scene_id' => $scene->id,
                'name' => $key
            ]);
            foreach ($value as $good) {
                array_push($goods, [
                    'scene_category_id' => $sceneCategory->id,
                    'image_id' => $good['image_id'],
                    'name' => $good['name'],
                    'describe' => $good['describe'],
                    'url' => $good['url']
                ]);
            }
        }
        SceneGood::saveAll($goods);

        return $this->created();
    }

    public function destroy($id)
    {
        \DB::transaction(function () use ($id) {
            Scene::destroy($id);
            $categoryId = SceneCategory::where('scene_id', $id)->pluck('id');
            SceneCategory::where('scene_id', $id)->delete();
            SceneGood::whereIn('scene_category_id', $categoryId)->delete();
        });
        return $this->message('删除成功');
    }
}