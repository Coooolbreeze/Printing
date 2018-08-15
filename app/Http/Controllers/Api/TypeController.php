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
use App\Models\SecondaryType;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends ApiController
{
    public function index()
    {
        return $this->success(new TypeCollection(Type::pagination()));
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

            if (isset($request->category_id)) {
                CategoryItem::create([
                    'category_id' => $request->category_id,
                    'item_id' => $type->id,
                    'item_type' => 1
                ]);
            }
        });

        return $this->created();
    }

    /**
     * @param Request $request
     * @param Type $type
     * @throws \Throwable
     */
    public function update(Request $request, Type $type)
    {
        \DB::transaction(function () use ($request, $type) {
            Type::updateField($request, $type, ['name', 'image_id', 'detail']);

            if (isset($request->category_id)) {
                CategoryItem::where('item_id', $type->id)
                    ->where('type', 1)
                    ->update(['category_id' => $request->category_id]);
            }
        });
    }

    /**
     * @param Type $type
     * @return mixed
     * @throws BaseException
     */
    public function destroy(Type $type)
    {
        if ($type->entities()->count() > 0)
            throw new BaseException('该类型下已有商品，不可删除');

        $type->delete();

        return $this->message('删除成功');
    }
}