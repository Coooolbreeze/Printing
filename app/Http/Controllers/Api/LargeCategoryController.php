<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/5
 * Time: 15:01
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\CategoryResource;
use App\Http\Resources\LargeCategoryResource;
use App\Models\LargeCategory;
use Illuminate\Http\Request;

class LargeCategoryController extends ApiController
{
    public function index()
    {
        return $this->success(LargeCategoryResource::collection(LargeCategory::all()));
    }

    public function show(LargeCategory $largeCategory)
    {
        return $this->success(CategoryResource::collection($largeCategory->categories)->hide(['items']));
    }

    public function update(Request $request, LargeCategory $largeCategory)
    {
        LargeCategory::updateField($request, $largeCategory, ['image_id', 'name', 'url']);
    }
}