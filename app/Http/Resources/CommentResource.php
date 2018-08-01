<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/1
 * Time: 15:17
 */

namespace App\Http\Resources;


class CommentResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'user' => (new UserResource($this->user))->show(['id', 'nickname', 'avatar']),
            'body' => $this->body,
            'target' => $this->when($this->target, $this->target),
            'grade' => $this->when($this->grade, $this->grade),
            'created_at' => (string)$this->created_at
        ]);
    }
}