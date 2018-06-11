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
        $sceneGood->update([
            'image_id' => $request->image_id,
            'name' => $request->name,
            'describe' => $request->describe,
            'url' => $request->url
        ]);
        return $this->message('更新成功');
    }

    public function destroy(SceneGood $sceneGood)
    {
        $sceneGood->delete();
        return $this->message('删除成功');
    }
}