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
    public function store(StoreComment $request)
    {
        Comment::create([
            'user_id' => TokenFactory::getCurrentUID(),
            'commentable_id' => $request->commentable_id,
            'commentable_type' => 'App\Models\\' . ($request->commentable_type ?: 'Entity'),
            'body' => $request->body,
            'target' => $request->target,
            'grade' => $request->grade
        ]);
        return $this->created();
    }
}