<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 14:21
 */

namespace App\Http\Resources;


use App\Models\CategoryItem;
use App\Models\Combination;
use App\Models\Comment;
use App\Services\Tokens\TokenFactory;

class EntityResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new EntityResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'category' => $this->when($this->getCategory(), $this->getCategory()),
            'type' => (new TypeResource($this->type))->show(['id', 'name']),
            'secondary_type' => $this->when(
                $this->secondaryType,
                (new SecondaryTypeResource($this->secondaryType))
                    ->show(['id', 'name'])
            ),
            'image' => new ImageResource($this->images()->first()),
            'images' => ImageResource::collection($this->images),
            'name' => $this->name,
            'summary' => $this->summary,
            'body' => $this->body,
            'is_follow' => $this->isFollow(),
            'lead_time' => $this->lead_time,
            'custom_number' => $this->custom_number,
            'unit' => $this->unit,
            'title' => $this->title,
            'keywords' => $this->keywords,
            'describe' => $this->describe,
            'price' => $this->getPrice(),
            'specs' => AttributeResource::collection($this->attributes),
            'custom_specs' => CustomAttributeResource::collection($this->customAttributes),
            'combinations' => CombinationResource::collection($this->combinations()->active()->get()),
            'disabled_combinations' => $this->combinations()->disabled()->pluck('combination'),
            'comments' => new CommentCollection($this->comments()->paginate(Comment::getLimit())),
            'comment_count' => $this->comments()->count(),
            'free_express' => config('setting.free_express'),
            'status' => $this->convertStatus($this->status),
            'sales' => $this->sales,
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertStatus($value)
    {
        $status = [
            0 => '未上架',
            1 => '销售中'
        ];
        return $status[$value];
    }

    public function getCategory()
    {
        $categoryItem = CategoryItem::where('item_type', 2)
            ->where('item_id', $this->id)
            ->first();
        if ($categoryItem) return (new CategoryItemResource($categoryItem))->show(['category', 'is_hot', 'is_new']);
        else return false;
    }

    public function getPrice()
    {
        $combination = $this->combinations()->active()->orderBy('price')->first();

        if (!$combination) {
            return '暂无';
        }

        $price = $combination->price;

        $customSpecs = CustomAttributeResource::collection($this->customAttributes);
        foreach ($customSpecs as $spec) {
            foreach ($spec['values'] as $value) {
                $price *= $value['min'];
            }
        }

        if ($this->custom_number > 0) {
            return $price . '/' . $this->unit . '起';
        }

        $combinations = $this->combinations()->active()->orderBy('id', 'desc')->get()->toArray();

        foreach ($combinations as $key => $combination) {
            $number = explode('|', $combination['combination']);
            preg_match_all('/\d+/', end($number), $num);

            if (count($combinations) == $key + 1) {
                preg_match_all('/\D+/', end($number), $str);
                return (number_format(ceil($combination['price'] / $num[0][0] * 100) / 100, 2)) . '/' . end($str[0]) . '起';
            }
            $next = explode('|', $combinations[$key + 1]['combination']);
            preg_match_all('/\d+/', end($next), $num1);
            if ($num > $num1) {
                preg_match_all('/\D+/', end($number), $str);
                return (number_format(ceil($combination['price'] / $num[0][0] * 100) / 100, 2)) . '/' . end($str[0]) . '起';
            }
        }
    }

    public function isFollow()
    {
        try {
            $ids = TokenFactory::getCurrentUser()->entities()->pluck('id')->toArray();
            return in_array($this->id, $ids);
        } catch (\Exception $exception) {
            return false;
        }
    }
}