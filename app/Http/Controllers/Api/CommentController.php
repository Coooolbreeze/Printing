<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/1
 * Time: 15:08
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderStatusEnum;
use App\Exceptions\BaseException;
use App\Http\Requests\StoreComment;
use App\Models\Comment;
use App\Models\Order;
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

            $order = Order::findOrFail($request->order_id);

            self::validateOrderStatus($order);

            foreach ($request->comments as $value) {
                $comment = Comment::create([
                    'user_id' => TokenFactory::getCurrentUID(),
                    'commentable_id' => $value['commentable_id'],
                    'commentable_type' => 'App\Models\\' . self::getCommentableType($value),
                    'target' => $value['target'],
                    'goods_comment' => $value['goods_comment'],
                    'service_comment' => $value['service_comment'],
                    'describe_grade' => $value['describe_grade'],
                    'seller_grade' => $value['seller_grade'],
                    'logistics_grade' => $value['logistics_grade'],
                    'is_anonymous' => $value['is_anonymous']
                ]);
                $comment->images()->sync($value['images']);
                $order->update(['status' => OrderStatusEnum::COMMENTED]);
            }
        });

        return $this->created();
    }

    /**
     * @param $order
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public static function validateOrderStatus($order)
    {
        if (!TokenFactory::isValidOperate($order->user_id))
            throw new BaseException('不能评价他人订单');
        if ($order->status == OrderStatusEnum::COMMENTED)
            throw new BaseException('该订单已评价');
        if ($order->status != OrderStatusEnum::RECEIVED)
            throw new BaseException('订单未收货，不能评价');
    }

    /**
     * @param $value
     * @return null|string
     */
    public static function getCommentableType($value)
    {
        $commentableType = null;
        try {
            $commentableType = $value['commentable_type'];
        } catch (\Exception $exception) {
            $commentableType = 'Entity';
        }
        return $commentableType;
    }
}