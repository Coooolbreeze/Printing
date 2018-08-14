<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/14
 * Time: 16:06
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\NewsCategoryResource;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryController extends ApiController
{
    public function index()
    {
        return $this->success(
            NewsCategoryResource::collection(NewsCategory::all())
        );
    }

    public function show(NewsCategory $newsCategory)
    {
        return $this->success(new NewsCategoryResource($newsCategory));
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        NewsCategory::updateField($request, $newsCategory, ['title']);
    }
}