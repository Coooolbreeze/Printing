<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/3
 * Time: 10:10
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderPayTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Events\OrderPaid;
use App\Events\OrderRefunded;
use App\Exceptions\BaseException;
use App\Http\Requests\UpdateOrderApply;
use App\Http\Resources\OrderApplyCollection;
use App\Models\BalanceRecord;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderApply;
use App\Services\AliPay;
use App\Services\Tokens\TokenFactory;
use App\Services\WxPay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderApplyController extends ApiController
{
    public function index(Request $request)
    {
        $orderApply = (new OrderApply())
            ->when($request->begin_time, function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $request->end_time))
                ]);
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('status')
            ->latest()
            ->paginate(OrderApply::getLimit());

        return $this->success(new OrderApplyCollection($orderApply));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public function store(Request $request)
    {
        $order = Order::where('order_no', $request->order_no)->first();

        if ($request->type == 1) {
            if ($order->status != OrderStatusEnum::PAID) {
                throw new BaseException('该订单不能退款');
            }

            $count = $order->applies()
                ->where('status', '0')
                ->where('type', 1)
                ->count();
            if ($count > 0) {
                throw new BaseException('已提交退款申请，请不要重复提交');
            }
        }

        if ($request->type == 2) {
            if ($order->status != OrderStatusEnum::UNPAID) {
                throw new BaseException('该订单不能申请后台支付');
            }

            $count = $order->applies()
                ->where('status', '0')
                ->where('type', 2)
                ->count();
            if ($count > 0) {
                throw new BaseException('已提交后台支付申请，请不要重复提交');
            }
        }

        OrderApply::create([
            'user_id' => TokenFactory::getCurrentUID(),
            'order_no' => $request->order_no,
            'price' => $order->total_price,
            'channel' => $request->channel,
            'type' => $request->type
        ]);

        return $this->created();
    }

    /**
     * @param UpdateOrderApply $request
     * @param OrderApply $orderApply
     * @return mixed
     * @throws \Throwable
     */
    public function update(UpdateOrderApply $request, OrderApply $orderApply)
    {
        \DB::transaction(function () use ($request, $orderApply) {
            $type = $orderApply->type == 1 ? '退款' : '后台支付';

            if ($request->status == 1) {
                if ($orderApply->type == 1) {
                    self::refund($orderApply->order_no);
                } else {
                    self::backPay($orderApply->order_no);
                }
                Message::send($orderApply->order->user_id, $type . '申请通过', '您的' . $type . '申请已通过！');

                $orderApply->update([
                    'status' => 1
                ]);
            } elseif ($request->status == 2) {
                Message::send($orderApply->order->user_id, $type . '申请驳回通知', '您的' . $type . '申请已被驳回！');

                $orderApply->update([
                    'status' => 2
                ]);
            }
        });

        return $this->message('更新成功');
    }

    /**
     * @param $orderNo
     * @throws \Throwable
     */
    public static function refund($orderNo)
    {
        \DB::transaction(function () use ($orderNo) {
            $order = Order::where('order_no', $orderNo)
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->pay_type == OrderPayTypeEnum::ALI_PAY) {
                (new AliPay())->setOrder($order)->refund();
            } elseif ($order->pay_type == OrderPayTypeEnum::WX_PAY) {
                (new WxPay())->setOrder($order)->refund();
            } elseif ($order->pay_type == OrderPayTypeEnum::BALANCE) {
                BalanceRecord::income($order->total_price, '订单退款', $order->user);
                $order->update([
                    'status' => OrderStatusEnum::REFUNDED
                ]);
            } else {
                throw new BaseException('该订单不能退款');
            }

            event(new OrderRefunded($order));
        });
    }

    /**
     * @param $orderNo
     * @throws BaseException
     */
    public static function backPay($orderNo)
    {
        $order = Order::where('order_no', $orderNo)
            ->lockForUpdate()
            ->first();

        if ($order->status == OrderStatusEnum::EXPIRE) {
            throw new BaseException('该订单已过期，无法支付');
        }
        if ($order->status >= OrderStatusEnum::PAID) {
            throw new BaseException('该订单已支付，请不要重复支付');
        }

        $order->update([
            'balance_deducted' => 0,
            'status' => OrderStatusEnum::PAID,
            'pay_type' => OrderPayTypeEnum::BACK_PAY
        ]);

        event(new OrderPaid($order));
    }
}