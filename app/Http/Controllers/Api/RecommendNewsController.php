<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/21
 * Time: 15:40
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\RecommendNewsResource;
use App\Models\RecommendNews;
use Illuminate\Http\Request;

class RecommendNewsController extends ApiController
{
    public function index()
    {
        $result = [];
        RecommendNews::all()->each(function ($news) use (&$result) {
            if (array_key_exists($news->category, $result))
                array_push($result[$news->category], new RecommendNewsResource($news));
            else
                $result[$news->category] = [new RecommendNewsResource($news)];
        });
        return $this->success($result);
    }

    public function update(Request $request, RecommendNews $recommendNews)
    {
        RecommendNews::updateField($request, $recommendNews, ['title', 'url']);
        return $this->message('更新成功');
    }
}