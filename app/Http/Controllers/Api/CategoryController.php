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
use App\Models\Category;
use App\Models\Entity;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
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
        if ($category->items()->count() > 0)
            throw new BaseException('该分类下已有产品，无法删除');

        $category->delete();

        return $this->message('删除成功');
    }
}