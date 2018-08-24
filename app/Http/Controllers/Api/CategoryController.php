<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/5
 * Time: 15:01
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Resources\CategoryItemCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Entity;
use App\Models\LargeCategory;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function index(Request $request)
    {
        return $this->success([
            'data' => CategoryResource::collection(
                Category::where('large_category_id', $request->large_category_id)->get()
            )
        ]);
    }

    public function show(Category $category)
    {
        return $this->success(new CategoryItemCollection($category->items()->paginate(Entity::getLimit())));
    }

    public function store(Request $request)
    {
        Category::create([
            'large_category_id' => $request->large_category_id,
            'name' => $request->name,
            'url' => $request->url
        ]);

        return $this->created();
    }

    public function update(Request $request, Category $category)
    {
        Category::updateField($request, $category, ['name', 'url']);

        return $this->message('更新成功');
    }

    /**
     * @param Category $category
     * @return mixed
     * @throws BaseException
     */
    public function destroy(Category $category)
    {
        $categories = LargeCategory::find($category->large_category_id)->categories;
        if ($categories->count() == 1) {
            throw new BaseException('至少应有一个二级分类');
        }

        $categoryId = $categories->where('id', '<>', $category->id)->first()->id;
        $category->items()->update([
            'category_id' => $categoryId
        ]);

        $category->delete();

        return $this->message('删除成功');
    }
}