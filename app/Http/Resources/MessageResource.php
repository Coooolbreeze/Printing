<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/2
 * Time: 11:16
 */

namespace App\Http\Resources;


class MessageResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new MessageResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'user' => (new UserResource($this->user))->show(['id', 'nickname']),
            'sender' => $this->sender,
            'title' => $this->title,
            'body' => $this->body,
            'is_read' => (bool)$this->is_read,
            'created_at' => (string)$this->created_at
        ]);
    }
}