<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/7
 * Time: 14:55
 */

namespace App\Http\Resources;


use App\Enum\NewsStatusEnum;

class NewsResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new NewsResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'category' => (new NewsCategoryResource($this->newsCategory))->show(['id', 'title']),
            'image' => new ImageResource($this->image),
            'title' => $this->title,
            'from' => $this->from,
            'summary' => $this->summary,
            'body' => $this->body,
//            'sort' => $this->sort,
            'status' => $this->convertStatus($this->status)
        ]);
    }

    public function convertStatus($value)
    {
        $status = [
            NewsStatusEnum::UNPUBLISHED => '未发布',
            NewsStatusEnum::PUBLISHED => '已发布',
            NewsStatusEnum::TOP => '置顶'
        ];

        return $status[$value];
    }
}