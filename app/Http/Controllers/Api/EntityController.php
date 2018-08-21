<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/4
 * Time: 10:03
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\EntityCollection;
use App\Http\Resources\EntityResource;
use App\Models\Attribute;
use App\Models\CategoryItem;
use App\Models\Combination;
use App\Models\CustomAttribute;
use App\Models\CustomValue;
use App\Models\Entity;
use App\Models\Value;
use Illuminate\Http\Request;

class EntityController extends ApiController
{
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
            ->paginate(Entity::getLimit());

        return $this->success(new EntityCollection($entities));
    }

    public function show(Entity $entity)
    {
        return $this->success((new EntityResource($entity))->hide(['image']));
    }

    /**
     * 添加商品
     *
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $entity = null;
        \DB::transaction(function () use ($request, &$entity) {
            // 创建商品
            $entity = Entity::create([
                'type_id' => $request->category_id,
                'secondary_type_id' => $request->secondary_type_id,
                'name' => $request->name,
                'summary' => $request->summary,
                'body' => $request->body,
                'lead_time' => $request->lead_time,
                'custom_number' => $request->custom_number ?: 0,
                'title' => $request->title,
                'keywords' => $request->keywords,
                'describe' => $request->describe
            ]);

            if (isset($request->category_id)) {
                CategoryItem::create([
                    'category_id' => $request->category_id,
                    'item_id' => $entity->id,
                    'item_type' => 2
                ]);
            }

            // 同步商品相册
            $entity->images()->sync($request->images);

            // 同步用户自定义属性
            isset($request->custom_specs) && self::customSpecs($entity, $request->custom_specs);

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

            if (isset($request->category_id)) {
                CategoryItem::where('item_id', $entity->id)
                    ->where('type', 2)
                    ->update(['category_id' => $request->category_id]);
            }
        });

        return $this->message('更新成功');
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