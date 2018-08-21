<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/21
 * Time: 16:58
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\RecommendNewEntityResource;
use App\Models\RecommendNewEntity;
use Illuminate\Http\Request;

class RecommendNewEntityController extends ApiController
{
    public function index()
    {
        return $this->success(RecommendNewEntityResource::collection(RecommendNewEntity::all()));
    }

    public function update(Request $request, RecommendNewEntity $recommendNewEntity)
    {
        RecommendNewEntity::updateField($request, $recommendNewEntity, ['image_id', 'url']);
        return $this->message('更新成功');
    }
}