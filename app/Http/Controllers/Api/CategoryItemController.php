<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/7
 * Time: 9:31
 */

namespace App\Http\Controllers\Api;


use App\Models\CategoryItem;
use Illuminate\Http\Request;

class CategoryItemController extends ApiController
{
    public function store(Request $request)
    {
        CategoryItem::create([
            'category_id' => $request->category_id,
            'item_id' => $request->item_id,
            'item_type' => $request->item_type
        ]);
        return $this->created();
    }

    public function update(Request $request, CategoryItem $categoryItem)
    {
        CategoryItem::updateField($request, $categoryItem, ['is_hot', 'is_new']);

        return $this->message('更新成功');
    }

    public function destroy(CategoryItem $categoryItem)
    {
        $categoryItem->delete();

        return $this->message('删除成功');
    }
}