<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 17:16
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\HelpCategoryCollection;
use App\Http\Resources\HelpCategoryResource;
use App\Models\HelpCategory;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class HelpCategoryController extends ApiController
{
    public function index()
    {
        return $this->success(HelpCategoryResource::collection(HelpCategory::all()));
    }

    public function show(HelpCategory $helpCategory)
    {
        return $this->success(new HelpCategoryResource($helpCategory));
    }

    public function store(Request $request)
    {
        HelpCategory::create([
            'name' => $request->name
        ]);
        return $this->created();
    }

    public function update(Request $request, HelpCategory $helpCategory)
    {
        $helpCategory->update([
            'name' => $request->name
        ]);
        return $this->message('更新成功');
    }

    public function destroy(HelpCategory $helpCategory)
    {
        $helpCategory->delete();
        return $this->message('删除成功');
    }

    public function batchDelete(Request $request)
    {
        HelpCategory::whereIn('id', $request->ids)
            ->delete();
        return $this->message('删除成功');
    }
}