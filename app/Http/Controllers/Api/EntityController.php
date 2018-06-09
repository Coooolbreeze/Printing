<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/4
 * Time: 10:03
 */

namespace App\Http\Controllers\Api;


use App\Models\Attribute;
use App\Models\Combination;
use App\Models\Entity;
use App\Models\Value;
use Illuminate\Http\Request;

class EntityController extends ApiController
{
    /**
     * 添加商品
     *
     *{
     *  category_id: 1,
     *  name: '名称',
     *  summary: '描述',
     *  body: '详情',
     *  lead_time: '出货周期',
     *  images: [id1,id2],
     *  specs: {
     *    'attr1': [value1, value2],
     *    'attr2': [value3, value4]
     *  }
     *}
     *
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        \DB::transaction(function () use ($request) {
            // 创建商品
            $entity = Entity::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'summary' => $request->summary,
                'body' => $request->body,
                'lead_time' => $request->lead_time
            ]);

            // 同步商品相册
            $entity->images()->sync($request->images);

            // 同步商品属性
            $valuesArr = [];
            $values = [];
            foreach ($request->specs as $key => $value) {
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
        });

        return $this->created();
    }

    public static function combination($arr, &$combination, $str = '', $num = 0)
    {
        if ($num == count($arr)) return;

        foreach ($arr[$num] as $value) {
            $value = $str . $value;

            if ($num == count($arr) - 1) array_push($combination, $value);
            else $value .= '|';

            self::combination($arr, $combination, $value, $num + 1);
        }
    }
}