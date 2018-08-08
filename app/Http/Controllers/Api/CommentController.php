<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/1
 * Time: 15:08
 */

namespace App\Http\Controllers\Api;


use App\Http\Requests\StoreComment;
use App\Models\Comment;
use App\Services\Tokens\TokenFactory;

class CommentController extends ApiController
{
    /**
     * @param StoreComment $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(StoreComment $request)
    {
        \DB::transaction(function () use ($request) {
            $comment = Comment::create([
                'user_id' => TokenFactory::getCurrentUID(),
                'commentable_id' => $request->commentable_id,
                'commentable_type' => 'App\Models\\' . ($request->commentable_type ?: 'Entity'),
                'target' => $request->target,
                'goods_comment' => $request->goods_comment,
                'service_comment' => $request->service_comment,
                'describe_grade' => $request->describe_grade,
                'seller_grade' => $request->seller_grade,
                'logistics_grade' => $request->logistics_grade,
                'is_anonymous' => $request->is_anonymous
            ]);

            $comment->images()->sync($request->images);
        });

        return $this->created();
    }
}