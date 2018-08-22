<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/5
 * Time: 15:01
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\CategoryResource;
use App\Http\Resources\EntityResource;
use App\Http\Resources\LargeCategoryResource;
use App\Models\Entity;
use App\Models\LargeCategory;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class LargeCategoryController extends ApiController
{
    public function index()
    {
        return $this->success(LargeCategoryResource::collection(LargeCategory::all()));
    }

    public function show(LargeCategory $largeCategory)
    {
        if (TokenFactory::isAdmin()) {
            return $this->success(CategoryResource::collection($largeCategory->categories)->hide(['items']));
        }
        else {
            $entities = [];
            $largeCategory->categories->each(function ($category) use (&$entities) {
                $category->items->each(function ($item) use (&$entities) {
                    if ($item->item_type == 2) array_push($entities, $item->entity);
                    else $item->type->entities->each(function ($entity) use (&$entities) {
                        array_push($entities, $entity);
                    });
                });
            });

            $currentPage = (int)\request('page', 1);
            $limit = Entity::getLimit();

            $collection = collect($entities);
            $entities = $collection->sortByDesc('sales')->values()->forPage($currentPage, $limit);
            $count = count($entities);
            $total = $collection->count();
            $lastPage = ceil($total / $limit);
            $hasMorePage = $currentPage < $lastPage;

            return $this->success([
                'large_categories' => LargeCategoryResource::collection(LargeCategory::all())->show(['id', 'name']),
                'data' => EntityResource::collection($entities)->show(['id', 'image', 'name', 'summary', 'status', 'sales', 'comment_count']),
                'count' => $count,
                'total' => $total,
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'has_more_pages' => $hasMorePage
            ]);
        }
    }

    public function update(Request $request, LargeCategory $largeCategory)
    {
        LargeCategory::updateField($request, $largeCategory, ['image_id', 'name', 'url']);
    }
}