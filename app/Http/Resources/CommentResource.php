<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/1
 * Time: 15:17
 */

namespace App\Http\Resources;


use App\Services\Tokens\TokenFactory;

class CommentResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'user' => $this->when(
                TokenFactory::isAdmin() || $this->is_anonymous != 0,
                (new UserResource($this->user))->show(['id', 'nickname', 'avatar'])
            ),
            'goods' => $this->getCommentEl($this->commentable),
            'target' => $this->when($this->target, $this->target),
            'goods_comment' => $this->goods_comment,
            'service_comment' => $this->service_comment,
            'images' => ImageResource::collection($this->images),
            'describe_grade' => $this->describe_grade,
            'seller_grade' => $this->seller_grade,
            'logistics_grade' => $this->logistics_grade,
            'is_anonymous' => (bool)$this->is_anonymous,
            'created_at' => (string)$this->created_at
        ]);
    }

    public function getCommentEl($commentable)
    {
        $class = get_class($commentable);

        if ($class == 'App\Models\Entity') {
            return (new EntityResource($commentable))->show(['id', 'name']);
        }
    }
}