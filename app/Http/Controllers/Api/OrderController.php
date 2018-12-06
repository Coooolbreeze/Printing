<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/26
 * Time: 16:24
 */

namespace App\Http\Controllers\Api;


use App\Enum\OrderPayTypeEnum;
use App\Enum\OrderStatusEnum;
use App\Events\OrderAudited;
use App\Events\OrderDelivered;
use App\Events\OrderExpire;
use App\Events\OrderFailed;
use App\Events\OrderPaid;
use App\Events\OrderReceived;
use App\Exceptions\BaseException;
use App\Exports\OrderExport;
use App\Http\Requests\StoreBackOrder;
use App\Http\Requests\StoreOrder;
use App\Http\Requests\UpdateOrder;
use App\Http\Resources\FileResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Combination;
use App\Models\Entity;
use App\Models\Express;
use App\Models\File;
use App\Models\Order;
use App\Models\Receipt;
use App\Models\User;
use App\Models\UserCoupon;
use App\Services\Tokens\TokenFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends ApiController
{
    /**
     * 订单标题长度
     *
     * @var int
     */
    private static $orderTitleLength = 15;

    public function index(Request $request)
    {
        $orders = (new Order())
            ->when(isset($request->status) && $request->status != null, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->begin_time, function ($query) use ($request) {
                $query->whereBetween('created_at', [
                    Carbon::parse(date('Y-m-d H:i:s', $request->begin_time)),
                    Carbon::parse(date('Y-m-d H:i:s', $request->end_time))
                ]);
            })
            ->when($request->order_no, function ($query) use ($request) {
                $query->where('order_no', $request->order_no);
            })
            ->when($request->person, function ($query) use ($request) {
                $query->where('snap_address', 'like', '{"name":"' . $this->unicodeEncode($request->person) . '",%');
            })
            ->when($request->member, function ($query) use ($request) {
                $query->whereHas('users', function ($query) use ($request) {
                    $query->where('nickname', $request->member);
                });
            })
            ->latest()
            ->paginate(Order::getLimit());

        return $this->success(new OrderCollection($orders));
    }

    private function unicodeEncode($name){
        $name = iconv('UTF-8', 'UCS-2', $name);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2)
        {
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0)
            {  // 两个字节的文字
                $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
            }
            else
            {
                $str .= $c2;
            }
        }
        return $str;
    }


    public function newOrder()
    {
        $orders = (new Order())
            ->whereNotNull('paid_at')
            ->latest()
            ->limit(10)
            ->get();

        return $this->success(OrderResource::collection($orders)->show(['id', 'order_no', 'user', 'title', 'created_at']));
    }

    public function export(Request $request)
    {
        return Excel::download(new OrderExport($request), 'orders.xlsx');
    }

    public function statusList()
    {
        return $this->success([
            '待支付' => [
                'value' => OrderStatusEnum::UNPAID,
                'count' => Order::unpaid()->count()
            ],
            '待审核' => [
                'value' => OrderStatusEnum::PAID,
                'count' => Order::paid()->count()
            ],
            '待发货' => [
                'value' => OrderStatusEnum::UNDELIVERED,
                'count' => Order::undelivered()->count()
            ],
            '待收货' => [
                'value' => OrderStatusEnum::DELIVERED,
                'count' => Order::delivered()->count()
            ]
        ]);
    }

    /**
     * @param Order $order
     * @return mixed
     * @throws \App\Exceptions\ForbiddenException
     * @throws \App\Exceptions\TokenException
     */
    public function show(Order $order)
    {
        if (TokenFactory::isValidOperate($order->user_id) || TokenFactory::can('用户管理')) {
            return $this->success(new OrderResource($order));
        }
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return $this->message('删除成功');
    }

    /**
     * @param StoreOrder $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(StoreOrder $request)
    {
        $order = null;

        \DB::transaction(function () use ($request, &$order) {
            $goodsInfo = $request->ids
                ? self::cartOrder($request->ids)
                : self::entityOrder($request->entity);

            $snapAddress = Address::addressSnap($request->address_id);

            $freight = Express::getFreight(
                $request->express_id,
                json_decode($snapAddress, true)['province'],
                $goodsInfo['total_price'],
                $goodsInfo['total_weight']
            );

            $order = [
                'order_no' => 'E-' . makeOrderNo(),
                'user_id' => TokenFactory::getCurrentUID(),
                'express_id' => $request->express_id,
                'title' => $goodsInfo['title'],
                'goods_count' => $goodsInfo['total_count'],
                'goods_price' => $goodsInfo['total_price'],
                'total_weight' => $goodsInfo['total_weight'],
                'freight' => $freight,
                'snap_address' => $snapAddress,
                'snap_content' => json_encode($goodsInfo['goods']),
                'remark' => $request->remark
            ];

            $money = $order['goods_price'] + $order['freight'];

            if ($request->coupon_no) {
                $quota = UserCoupon::use($request->coupon_no, $goodsInfo['total_price']);
                $order['discount_amount'] = $quota;
                $order['coupon_no'] = $request->coupon_no;
                $money -= $quota;
            }

            $order['member_discount'] = $money * (1 - TokenFactory::getCurrentUser()->memberLevel->discount / 10);

            $order['total_price'] = $money - $order['member_discount'];

            if ($request->receipt_info) {
                $receipt = Receipt::receipted($request->receipt_info, $order['total_price']);
                $order['receipt_id'] = $receipt->id;
            }

            $order = Order::create($order);
        });

        return $this->success(['id' => $order->id]);
    }

    /**
     * @param UpdateOrder $request
     * @param Order $order
     * @return mixed
     * @throws BaseException
     * @throws \App\Exceptions\ForbiddenException
     * @throws \App\Exceptions\TokenException
     */
    public function update(UpdateOrder $request, Order $order)
    {
        Order::updateField($request, $order, ['bill_remark']);

        if ($request->key && $request->file_id) {
            $content = json_decode($order->snap_content, true);
            foreach ($content as $key => &$entity) {
                if ($entity['key'] == $request->key) {
                    $entity['file'] = new FileResource(File::findOrFail($request->file_id));
                }
            }
            $order->update([
                'snap_content' => json_encode($content),
                'status' => OrderStatusEnum::PAID
            ]);
        } else {
            $status = $request->status;

            self::updateValidate($status, $order);

            Order::updateField($request, $order, ['status']);

            if ($request->remark) {
                $order->update([
                    'remark' => $order->remark . '--' . $request->remark
                ]);
            }

            // 分发事件
            if ($status == OrderStatusEnum::EXPIRE) {
                event(new OrderExpire($order));
            } elseif ($status == OrderStatusEnum::UNDELIVERED) {
                event(new OrderAudited($order));
            } elseif ($status == OrderStatusEnum::DELIVERED) {
                event(new OrderDelivered($order));
            } elseif ($status == OrderStatusEnum::RECEIVED) {
                event(new OrderReceived($order));
            } elseif ($status == OrderStatusEnum::FAILED) {
                event(new OrderFailed($order));
            }
        }

        return $this->message('更新成功');
    }

    /**
     * @param StoreBackOrder $request
     * @return mixed
     * @throws \Throwable
     */
    public function backOrder(StoreBackOrder $request)
    {
        \DB::transaction(function () use ($request, &$goodsInfo) {
            CartController::checkCartsInfo($request->entity);
            $cart = Cart::create(CartController::getSaveInfo($request->entity, $request->user_id));
            $goodsInfo = self::cartOrder([$cart->id], $request->user_id);

            $snapAddress = json_encode($request->address);

            $freight = Express::getFreight(
                $request->express_id,
                $request->address['province'],
                $goodsInfo['total_price'],
                $goodsInfo['total_weight']
            );

            $order = [
                'order_no' => 'E-' . makeOrderNo(),
                'user_id' => $request->user_id,
                'express_id' => $request->express_id,
                'title' => $goodsInfo['title'],
                'goods_count' => $goodsInfo['total_count'],
                'goods_price' => $goodsInfo['total_price'],
                'total_weight' => $goodsInfo['total_weight'],
                'total_price' => $goodsInfo['total_price'] + $freight,
                'freight' => $freight,
                'snap_address' => $snapAddress,
                'snap_content' => json_encode($goodsInfo['goods']),
                'remark' => $request->remark,
                'creator' => TokenFactory::getCurrentUser()->nickname
            ];

            Order::create($order);

//            $order->update([
//                'status' => OrderStatusEnum::PAID,
//                'pay_type' => OrderPayTypeEnum::BACK_PAY
//            ]);
//
//            event(new OrderPaid($order));
        });

        return $this->created();
    }

    /**
     * @param $entity
     * @return null
     * @throws \Throwable
     */
    private static function entityOrder($entity)
    {
        $goodsInfo = null;
        \DB::transaction(function () use ($entity, &$goodsInfo) {
            CartController::checkCartsInfo($entity);
            $cart = Cart::create(CartController::getSaveInfo($entity));
            $goodsInfo = self::cartOrder([$cart->id]);
        });
        return $goodsInfo;
    }

    /**
     * @param $ids
     * @param null $userId
     * @return array
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    private static function cartOrder($ids, $userId = null)
    {
        $user = $userId ? User::findOrFail($userId) : TokenFactory::getCurrentUser();
        $cartsAll = $user->carts()->pluck('id')->toArray();
        if (array_intersect($ids, $cartsAll) != $ids) throw new BaseException('提交订单中有不存在购物车中的数据');

        $carts = Cart::whereIn('id', $ids)->get();

        $goods = [];
        $totalWeight = 0;
        $totalPrice = 0;
        $totalCount = 0;
        $title = '';
        $carts->each(function ($value, $key) use (&$goods, &$totalWeight, &$totalPrice, &$totalCount, &$title) {
            $combination = Combination::findOrFail($value->combination_id);
            $entityModel = Entity::findOrFail($value->entity_id);

            $entity = [
                'key' => $key + 1,
                'id' => $entityModel->id,
                'name' => $entityModel->name,
                'type' => $entityModel->type->name,
                'image' => new ImageResource($entityModel->images()->first()),
                'combination' => [
                    'id' => $combination->id,
                    'name' => $value->count ? $combination->combination : substr($combination->combination, 0, strripos($combination->combination, '|'))
                ],
                'specs' => $value->count ? json_decode($value->specs, true) : array_slice(json_decode($value->specs, true), 0, -1),
                'custom_specs' => json_decode($value->custom_specs, true),
                'weight' => $value->weight,
                'count' => $value->count ?: substr($combination->combination, strripos($combination->combination, '|') + 1),
                'price' => $value->price,
                'grade' => self::getGrade($entityModel),
                'remark' => self::joinSpecs(json_decode($value->custom_specs, true)) . $value->remark
            ];

            ($value->file_id != 0) && $entity['file'] = new FileResource(File::findOrFail($value->file_id));

            array_push($goods, $entity);

            $totalWeight += $entity['weight'];
            $totalPrice += $entity['price'];
            $totalCount += self::getCount($entity['count']);
            $title .= $entity['name'] . ',';
        });

        $title = rtrim($title, ',');
        if (mb_strlen($title) <= self::$orderTitleLength)
            $title .= '共' . $totalCount . '件';
        else
            $title = mb_substr($title, 0, 14) . '...共' . $totalCount . '件';

        Cart::whereIn('id', $ids)->delete();

        return [
            'title' => $title,
            'goods' => $goods,
            'total_weight' => $totalWeight,
            'total_price' => $totalPrice,
            'total_count' => $totalCount
        ];
    }

    private static function joinSpecs($specs)
    {
        $str = '';
        foreach ($specs as $key => $spec) {
            $str .= $key . '：';
            foreach ($spec as $k => $v) $str .= $k . $v . 'CM*';
            $str = rtrim($str, '*') . '；';
        }
        return $str;
    }

    private static function getGrade(Entity $entity)
    {
        $comments = $entity->comments;

        if ($comments->count() == 0) {
            return 0;
        } else {
            $grade = $comments->sum('describe_grade') / $comments->count();

//                $arr = explode($grade, '.');
//                if ($arr[1] >= 5) {
//                    $arr[1] = 5;
//                } else {
//                    $arr[1] = 0;
//                }

//                $entity['grade'] = implode('.', $arr);

            return floor($grade * 100) / 100;
        }
    }

    private static function getCount($count)
    {
        if (!is_numeric($count)) {
            preg_match_all('/\d+/', $count, $arr);
            $count = $arr[0][0];
        }
        return $count;
    }

    /**
     * @param $status
     * @param $order
     * @throws BaseException
     * @throws \App\Exceptions\ForbiddenException
     * @throws \App\Exceptions\TokenException
     */
    private static function updateValidate($status, $order)
    {
        if ($status == OrderStatusEnum::EXPIRE) {
            TokenFactory::isValidOperate($order->user_id) || TokenFactory::can('订单管理');
        } elseif ($status == OrderStatusEnum::UNDELIVERED || $status == OrderStatusEnum::FAILED) {
            TokenFactory::can('订单管理');
            if ($order->status >= OrderStatusEnum::UNDELIVERED) {
                throw new BaseException('该订单已审核');
            }
        } elseif ($status == OrderStatusEnum::DELIVERED) {
            TokenFactory::can('订单管理');
            if ($order->status >= OrderStatusEnum::DELIVERED) {
                throw new BaseException('该订单已发货');
            }
        } elseif ($status == OrderStatusEnum::RECEIVED) {
            if (!TokenFactory::isValidOperate($order->user_id)) {
                throw new BaseException('不能操作别人的订单');
            }
            if ($order->status >= OrderStatusEnum::RECEIVED) {
                throw new BaseException('该订单已确认收货');
            }
        }
    }
}