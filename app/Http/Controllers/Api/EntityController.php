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
use App\Models\Combination;
use App\Models\CustomAttribute;
use App\Models\CustomValue;
use App\Models\Entity;
use App\Models\Value;
use Illuminate\Http\Request;

class EntityController extends ApiController
{
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
                'category_id' => $request->category_id,
                'name' => $request->name,
                'summary' => $request->summary,
                'body' => $request->body,
                'lead_time' => $request->lead_time,
                'custom_number' => $request->custom_number ?: 0,
                'title' => $request->title,
                'keywords' => $request->keywords,
                'describe' => $request->describe
            ]);

            // 同步商品相册
            $entity->images()->sync($request->images);

            // 同步用户自定义属性
            self::customSpecs($entity, $request->custom_specs);

            // 同步商品属性
            self::syncSpecs($entity, $request->specs);
        });

        return $this->success(['id' => $entity->id]);
    }

    public static function customSpecs($entity, $specs)
    {
        $valueArr = [];
        foreach ($specs as $key => $values) {
            $attribute = CustomAttribute::create([
                'entity_id' => $entity->id,
                'name' => $key
            ]);
            foreach ($values as $value) {
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
        foreach ($specs as $key => $value) {
            $attribute = Attribute::create([
                'entity_id' => $entity->id,
                'name' => $key
            ]);
            foreach ($value as $name) {
                array_push($values, [
                    'attribute_id' => $attribute->id,
                    'name' => $name
                ]);
            }

            array_push($valuesArr, $value);
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

    public function index(Request $request)
    {
        return $this->success(new EntityCollection(
            (new Entity())
                ->when($request->keyword, function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->keyword . '%')
                        ->orWhere('keywords', 'like', '%' . $request->keyword . '%');
                })
                ->paginate(Entity::getLimit())
        ));
    }

    public function show(Entity $entity)
    {
        return $this->success(new EntityResource($entity));
    }

    public function update(Request $request, Entity $entity)
    {
        isset($request->category_id) && $entity->category_id = $request->category_id;
        isset($request->name) && $entity->name = $request->name;
        isset($request->summary) && $entity->summary = $request->summary;
        isset($request->body) && $entity->body = $request->body;
        isset($request->lead_time) && $entity->lead_time = $request->lead_time;
        isset($request->title) && $entity->title = $request->title;
        isset($request->keywords) && $entity->keywords = $request->keywords;
        isset($request->describe) && $entity->describe = $request->describe;
        isset($request->images) && $entity->images()->sync($request->images);
        $entity->save();

        return $this->message('更新成功');
    }
}