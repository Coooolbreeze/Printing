<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 15:51
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Resources\TypeCollection;
use App\Http\Resources\TypeResource;
use App\Models\CategoryItem;
use App\Models\LargeCategoryItem;
use App\Models\SecondaryType;
use App\Models\Type;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class TypeController extends ApiController
{
    public function index()
    {
        if(TokenFactory::isAdmin()) {
            $types = new TypeCollection(Type::paginate());
        } else {
            $types = TypeResource::collection(Type::all())->show(['id', 'name']);
        }

        return $this->success($types);
    }

    public function show(Type $type)
    {
        return $this->success(new TypeResource($type));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        \DB::transaction(function () use ($request) {
            $type = Type::create([
                'name' => $request->name,
                'image_id' => $request->image_id,
                'detail' => $request->detail
            ]);

            if (isset($request->secondary_types)) {
                $secondaryTypes = [];
                foreach ($request->secondary_types as $name) {
                    array_push($secondaryTypes, [
                        'type_id' => $type->id,
                        'name' => $name
                    ]);
                }
                SecondaryType::saveAll($secondaryTypes);
            }

            if ($request->category_id) {
                CategoryItem::create([
                    'category_id' => $request->category_id,
                    'item_id' => $type->id,
                    'item_type' => 1,
                    'is_hot' => $request->is_hot,
                    'is_new' => $request->is_new
                ]);
            }
        });

        return $this->created();
    }

    /**
     * @param Request $request
     * @param Type $type
     * @return mixed
     * @throws \Throwable
     */
    public function update(Request $request, Type $type)
    {
        \DB::transaction(function () use ($request, $type) {
            Type::updateField($request, $type, ['name', 'image_id', 'detail']);

            if ($request->category_id) {
                CategoryItem::updateOrCreate(
                    ['item_id' => $type->id, 'item_type' => 1],
                    [
                        'category_id' => $request->category_id,
                        'is_hot' => $request->is_hot,
                        'is_new' => $request->is_new
                    ]
                );

                CategoryItem::where('item_type', 2)
                    ->whereIn('item_id', $type->entities()->pluck('id')->toArray())
                    ->delete();

                LargeCategoryItem::where('item_type', 2)
                    ->whereIn('item_id', $type->entities()->pluck('id')->toArray())
                    ->delete();
            } else {
                $categoryItem = CategoryItem::where('item_type', 1)
                    ->where('item_id', $type->id)
                    ->first();

                if ($categoryItem) {
                    $items = [];
                    $entities = $type->entities()->pluck('id')->toArray();
                    foreach ($entities as $id) {
                        array_push($items, [
                            'category_id' => $categoryItem->category_id,
                            'item_id' => $id,
                            'item_type' => 2
                        ]);
                    }
                    $categoryItem->delete();
                    CategoryItem::saveAll($items);
                }

                LargeCategoryItem::where('item_type', 1)
                    ->where('item_id', $type->id)
                    ->delete();
            }
        });

        return $this->message('更新成功');
    }

    /**
     * @param Type $type
     * @return mixed
     * @throws BaseException
     * @throws \Throwable
     */
    public function destroy(Type $type)
    {
        if ($type->entities()->count() > 0)
            throw new BaseException('该类型下已有商品，不可删除');

        \DB::transaction(function () use ($type) {
            CategoryItem::where('item_type', 1)
                ->where('item_id', $type->id)
                ->delete();

            LargeCategoryItem::where('item_type', 1)
                ->where('item_id', $type->id)
                ->delete();

            $type->delete();
        });

        return $this->message('删除成功');
    }
}