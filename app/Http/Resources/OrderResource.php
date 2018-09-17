<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/27
 * Time: 17:33
 */

namespace App\Http\Resources;


use App\Enum\OrderPayTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Models\Entity;
use App\Services\Tokens\TokenFactory;

class OrderResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new OrderResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'express' => (new ExpressResource($this->express))->show(['id', 'name']),
            'receipt' => $this->when($this->receipt_id, new ReceiptResource($this->receipt)),
            'order_no' => $this->order_no,
            'trade_no' => $this->when($this->trade_no, $this->trade_no),
            'user' => (new UserResource($this->user))->show(['id', 'nickname']),
            'title' => $this->title,
            'address' => json_decode($this->snap_address, true),
            'content' => $this->getContent(),
            'goods_price' => $this->goods_price,
            'goods_count' => $this->goods_count,
            'total_weight' => $this->total_weight,
            'freight' => $this->freight,
            'discount_amount' => $this->discount_amount,
            'member_discount' => $this->member_discount,
            'balance_deducted' => $this->when(
                $this->pay_type,
                $this->balance_deducted
            ),
            'total_price' => $this->total_price,
            'pay_price' => $this->when($this->pay_type, $this->total_price - $this->balance_deducted),
            'status' => self::convertStatus($this->status),
            'creator' => $this->when($this->creator, $this->creator),
            'pay_type' => $this->when(
                $this->pay_type,
                self::convertPayType($this->pay_type)
            ),
            'expresses' => $this->when(
                $this->status == OrderStatusEnum::DELIVERED,
                OrderExpressResource::collection($this->expresses)
            ),
            'logs' => $this->when(
                $this->pay_type && TokenFactory::isAdmin(),
                OrderLogResource::collection($this->logs()->latest()->get())
            ),
            'remark' => $this->remark,
            'created_at' => (string)$this->created_at,
            'paid_at' => $this->when(
                $this->pay_type,
                (string)$this->paid_at
            ),
            'audited_at' => $this->when(
                $this->audited_at,
                (string)$this->audited_at
            ),
            'delivered_at' => $this->when(
                $this->delivered_at,
                (string)$this->delivered_at
            ),
            'received_at' => $this->when(
                $this->received_at,
                (string)$this->received_at
            ),
            'refunded_at' => $this->when(
                $this->refunded_at,
                (string)$this->refunded_at
            )
        ]);
    }

    public static function convertStatus($value)
    {
        $status = [
            OrderStatusEnum::EXPIRE => '已失效',
            OrderStatusEnum::UNPAID => '待支付',
            OrderStatusEnum::PAID => '待审核',
            OrderStatusEnum::UNDELIVERED => '待发货',
            OrderStatusEnum::DELIVERED => '待收货',
            OrderStatusEnum::RECEIVED => '待评论',
            OrderStatusEnum::COMMENTED => '已评论',
            OrderStatusEnum::FAILED => '未通过',
            OrderStatusEnum::REFUNDED => '已退款'
        ];
        return $status[$value];
    }

    public static function convertPayType($value)
    {
        $payType = [
            OrderPayTypeEnum::ALI_PAY => '支付宝支付',
            OrderPayTypeEnum::WX_PAY => '微信支付',
            OrderPayTypeEnum::BALANCE => '余额支付',
            OrderPayTypeEnum::BACK_PAY => '后台支付'
        ];
        return $value ? $payType[$value] : null;
    }

    public function getContent()
    {
        $content = json_decode($this->snap_content, true);

        foreach ($content as &$entity) {
            $comments = Entity::find($entity['id'])->comments;

            if ($comments->count() == 0) {
                $entity['grade'] = 0;
            } else {
                $grade = $comments->sum('describe_grade') / $comments->count();

//                $arr = explode($grade, '.');
//                if ($arr[1] >= 5) {
//                    $arr[1] = 5;
//                } else {
//                    $arr[1] = 0;
//                }

//                $entity['grade'] = implode('.', $arr);

                $entity['grade'] = floor($grade * 100) / 100;
            }
        }

        return $content;
    }
}