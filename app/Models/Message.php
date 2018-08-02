<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/2
 * Time: 11:14
 */

namespace App\Models;


/**
 * App\Models\Message
 *
 * @property string $body
 * @property \Carbon\Carbon|null $created_at
 * @property int $id
 * @property int $is_read
 * @property string $sender
 * @property string $title
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUserId($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function welcome($id)
    {
        self::send($id, '欢迎注册易特印', '欢迎注册易特印！');
    }

    public static function memberLevelUp($id, MemberLevel $memberLevel)
    {
        self::send($id,
            '会员等级提升',
            '恭喜您的会员等级已经提升为' . $memberLevel->name .'啦！可享受全站购物' . $memberLevel->discount . '折优惠！'
        );
    }

    public static function orderPaid($id)
    {
        self::send($id, '订单支付提醒', '您的订单已成功支付，我们会尽快为您审核！');
    }

    public static function orderAudited($id)
    {
        self::send($id, '订单审核通过提醒','您的订单已审核通过，我们会尽快为您发货！');
    }

    public static function orderDelivered($id)
    {
        self::send($id, '订单发货提醒', '您的订单已发货，请耐心等待收货！');
    }

    public static function orderReceived($id)
    {
        self::send($id, '订单收货提醒', '您的订单已确认收货，祝您购物愉快！');
    }

    /**
     * @param $ids
     * @param $title
     * @param $body
     * @param string $sender
     * @return mixed
     */
    public static function send($ids, $title, $body, $sender = '系统消息')
    {
        if (!is_array($ids)) $ids = [$ids];

        $messages = [];
        foreach ($ids as $id) {
            array_push($messages, [
                'user_id' => $id,
                'sender' => $sender,
                'title' => $title,
                'body' => $body
            ]);
        }

        return self::saveAll($messages);
    }
}