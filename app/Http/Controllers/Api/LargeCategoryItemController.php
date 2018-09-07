<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/7
 * Time: 10:06
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\LargeCategoryItemCollection;
use App\Models\LargeCategoryItem;
use Illuminate\Http\Request;

class LargeCategoryItemController extends ApiController
{
    public function index(Request $request)
    {
        $items = LargeCategoryItem::where('large_category_id', $request->large_category_id)
            ->paginate(LargeCategoryItem::getLimit());

        return $this->success(new LargeCategoryItemCollection($items));
    }

    public function store(Request $request)
    {
        LargeCategoryItem::create([
            'large_category_id' => $request->large_category_id,
            'item_id' => $request->item_id,
            'item_type' => $request->item_type
        ]);
        return $this->created();
    }

    public function update(Request $request, LargeCategoryItem $largeCategoryItem)
    {
        LargeCategoryItem::updateField($request, $largeCategoryItem, ['is_hot', 'is_new']);

        return $this->message('更新成功');
    }

    public function delete(Request $request)
    {
        LargeCategoryItem::where('item_id', $request->item_id)
            ->where('item_type', $request->item_type)
            ->delete();

        return $this->message('删除成功');
    }
}