<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/4
 * Time: 10:03
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Requests\StoreEntity;
use App\Http\Resources\EntityCollection;
use App\Http\Resources\EntityResource;
use App\Models\Attribute;
use App\Models\CategoryItem;
use App\Models\Combination;
use App\Models\CustomAttribute;
use App\Models\CustomValue;
use App\Models\Entity;
use App\Models\LargeCategoryItem;
use App\Models\RecommendOther;
use App\Models\Type;
use App\Models\Value;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class EntityController extends ApiController
{
    public function all(Request $request)
    {
        if ($request->value) {
            $entities = EntityResource::collection(
                Entity::where('name', 'like', '%' . $request->value . '%')->get()
            )->show(['id', 'name']);
        } else {
            $entities = Type::with('entities')->get(['id', 'name']);
        }

        return $this->success($entities);
    }

    public function index(Request $request)
    {
        $entities = (new Entity())
            ->when($request->type_id, function ($query) use ($request) {
                $query->where('type_id', $request->type_id);
            })
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('keywords', 'like', '%' . $request->keyword . '%')
                    ->orWhere(function ($query) use ($request) {
                        $query->whereHas('type', function ($query) use ($request) {
                            $query->Where('name', 'like', '%' . $request->keyword . '%');
                        });
                    });
            })
            ->when($request->sort, function ($query) use ($request) {
                if ($request->sort == 1) {
                    $query->orderBy('sales', 'desc');
                }
            })
            ->paginate(Entity::getLimit());

        return $this->success(new EntityCollection($entities));
    }

    public function recommend()
    {
        if (config('setting.auto_recommend')) {
            $entities = Entity::inRandomOrder()->limit(4)->get();
        } else {
            $entities = Entity::whereIn('id',
                RecommendOther::where('entity_id', '>=', 100000)
                    ->pluck('entity_id')
                    ->toArray()
            )->get();
        }

        return $this->success(
            EntityResource::collection($entities)
                ->show(['id', 'image', 'name', 'type', 'summary', 'status', 'sales', 'price', 'comment_count'])
        );
    }

    public function show(Entity $entity)
    {
        return $this->success((new EntityResource($entity))->hide(['image']));
    }

    /**
     * 添加商品
     *
     * @param StoreEntity $request
     * @return mixed
     * @throws BaseException
     * @throws \Throwable
     */
    public function store(StoreEntity $request)
    {
        if (!$request->specs)
            throw new BaseException('请添加至少一个属性');

        $entity = null;
        \DB::transaction(function () use ($request, &$entity) {
            // 创建商品
            $entity = Entity::create([
                'type_id' => $request->type_id,
                'secondary_type_id' => $request->secondary_type_id,
                'name' => $request->name,
                'summary' => $request->summary,
                'body' => $request->body,
                'lead_time' => $request->lead_time,
                'custom_number' => $request->custom_number ?: 0,
                'unit' => $request->unit,
                'title' => $request->title,
                'keywords' => $request->keywords,
                'describe' => $request->describe
            ]);

            if ($request->category_id) {
                CategoryItem::create([
                    'category_id' => $request->category_id,
                    'item_id' => $entity->id,
                    'item_type' => 2,
                    'is_hot' => $request->is_hot,
                    'is_new' => $request->is_new
                ]);
            }

            // 同步商品相册
            $entity->images()->sync($request->images);

            // 同步用户自定义属性
            $request->custom_specs && count($request->custom_specs) > 0 && self::customSpecs($entity, $request->custom_specs);

            // 同步商品属性
            self::syncSpecs($entity, $request->specs);
        });

        return $this->success(['id' => $entity->id]);
    }

    /**
     * @param Request $request
     * @param Entity $entity
     * @return mixed
     * @throws \Throwable
     */
    public function update(Request $request, Entity $entity)
    {
        \DB::transaction(function () use ($request, $entity) {
            Entity::updateField($request, $entity, [
                'type_id', 'secondary_type_id', 'name', 'summary', 'body', 'lead_time', 'title', 'keywords', 'describe', 'status'
            ]);
            isset($request->images) && $entity->images()->sync($request->images);

            if ($request->category_id) {
                CategoryItem::updateOrCreate(
                    ['item_id' => $entity->id, 'item_type' => 2],
                    [
                        'category_id' => $request->category_id,
                        'is_hot' => $request->is_hot,
                        'is_new' => $request->is_new
                    ]
                );
            }
        });

        return $this->message('更新成功');
    }

    /**
     * @param Entity $entity
     * @return mixed
     * @throws \Throwable
     */
    public function destroy(Entity $entity)
    {
        \DB::transaction(function () use ($entity) {
            CategoryItem::where('item_type', 2)
                ->where('item_id', $entity->id)
                ->delete();

            LargeCategoryItem::where('item_type', 2)
                ->where('item_id', $entity->id)
                ->delete();

            $entity->delete();
        });

        return $this->message('删除成功');
    }

    public static function customSpecs($entity, $specs)
    {
        $valueArr = [];
        foreach ($specs as $spec) {
            $attribute = CustomAttribute::create([
                'entity_id' => $entity->id,
                'name' => $spec['attribute']
            ]);
            foreach ($spec['value'] as $value) {
                $value['custom_attribute_id'] = $attribute->id;
                array_push($valueArr, $value);
            }
        }
        CustomValue::saveAll($valueArr);
    }

    public static function syncSpecs($entity, $specs)
    {
        $valuesArr = [];
        $values = [];
        foreach ($specs as $spec) {
            $attribute = Attribute::create([
                'entity_id' => $entity->id,
                'name' => $spec['attribute']
            ]);
            foreach ($spec['value'] as $value) {
                array_push($values, [
                    'attribute_id' => $attribute->id,
                    'name' => $value
                ]);
            }

            array_push($valuesArr, $spec['value']);
        }
        Value::saveAll($values);

        // 组合商品属性
        $combinations = [];
        self::combination($valuesArr, $combinations);
        foreach ($combinations as &$combination) {
            $combination = [
                'entity_id' => $entity->id,
                'combination' => $combination
            ];
        }
        Combination::saveAll($combinations);
    }

    public static function combination($arr, &$combination, $str = '', $num = 0)
    {
        if ($num == count($arr)) return;

        foreach ($arr[$num] as $value) {
            $value = $str . $value;

            if ($num == count($arr) - 1) array_push($combination, $value);
            else $value .= config('setting.sku_separator');

            self::combination($arr, $combination, $value, $num + 1);
        }
    }
}