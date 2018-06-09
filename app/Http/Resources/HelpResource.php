<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 17:02
 */

namespace App\Http\Resources;


class HelpResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new HelpResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category' => (new HelpCategoryResource($this->category))->show(['id', 'title']),
            'title' => $this->title,
            'body' => $this->body,
            'updated_at' => (string)$this->updated_at
        ];
    }
}