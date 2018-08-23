<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 14:21
 */

namespace App\Http\Resources;


use App\Models\CategoryItem;
use App\Models\Comment;

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
            'lead_time' => $this->lead_time,
            'custom_number' => $this->custom_number,
            'title' => $this->title,
            'keywords' => $this->keywords,
            'describe' => $this->describe,
            'specs' => AttributeResource::collection($this->attributes),
            'custom_specs' => CustomAttributeResource::collection($this->customAttributes),
            'combinations' => CombinationResource::collection($this->combinations()->active()->get()),
            'disabled_combinations' => $this->combinations()->disabled()->pluck('combination'),
            'comments' => new CommentCollection($this->comments()->paginate(Comment::getLimit())),
            'comment_count' => $this->comments()->count(),
            'status' => $this->convertStatus($this->status),
            'sales' => $this->sales,
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertStatus($value)
    {
        $status = [
            1 => '销售中',
            2 => '已下架'
        ];
        return $status[$value];
    }

    public function getCategory()
    {
        $categoryItem = CategoryItem::where('item_type', 2)
            ->where('item_id', $this->id)
            ->first();
        if ($categoryItem) return (new CategoryResource($categoryItem->category))->hide(['items']);
        else return false;
    }
}