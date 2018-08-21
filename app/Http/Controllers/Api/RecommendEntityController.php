<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/21
 * Time: 16:59
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\RecommendEntityResource;
use App\Models\RecommendEntity;
use Illuminate\Http\Request;

class RecommendEntityController extends ApiController
{
    public function index()
    {
        $result = [];
        RecommendEntity::all()->each(function ($entity) use (&$result) {
            if (array_key_exists($entity->category, $result))
                array_push($result[$entity->category], new RecommendEntityResource($entity));
            else
                $result[$entity->category] = [new RecommendEntityResource($entity)];
        });
        return $this->success($result);
    }

    public function update(Request $request, RecommendEntity $recommendEntity)
    {
        RecommendEntity::updateField($request, $recommendEntity, ['image_id', 'url']);
        return $this->message('更新成功');
    }
}