<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function () {
    // 用户注册
    Route::post('/register', 'TokenController@register')->name('tokens.register');
    // 用户登录
    Route::post('/login', 'TokenController@login')->name('tokens.login');
    // 刷新token
    Route::post('/refresh', 'TokenController@refresh')->name('tokens.refresh');
    // 修改密码
    Route::put('/repassword', 'TokenController@rePassword')->name('token.rePassword');
    // 获取自己的资料
    Route::get('/users/self', 'UserController@self')->name('users.self');
    // 更新自己的资料
    Route::put('/users/self', 'UserController@selfUpdate')->name('users.selfUpdate');

    // 发送短信验证码
    Route::post('/sms', 'SmsController@sendSms');
    // 发送邮箱验证码
    Route::post('/email', 'EmailController@sendEmail');
    // 验证验证码
    Route::post('/code/validate', 'TokenController@validateVerificationCode');

    // 图片上传
    Route::apiResource('images', 'ImageController')
        ->only(['store']);
    // 文件上传
    Route::apiResource('files', 'FileController')
        ->only(['store']);

    Route::post('/alipay/notify', 'AliPayController@notify');
    Route::post('/wxpay/notify', 'WxPayController@notify');

    Route::get('/custom_service', 'SystemSettingController@customService');

    Route::apiResource('users', 'UserController')
        ->only(['show']);

    Route::apiResource('large_categories', 'LargeCategoryController')
        ->only(['index', 'show']);
    Route::get('/all/large_categories', 'LargeCategoryController@all');

    Route::apiResource('categories', 'CategoryController')
        ->only(['show']);

    Route::apiResource('types', 'TypeController')
        ->only(['index', 'show']);

    Route::get('/entities/recommend', 'EntityController@recommend');
    Route::apiResource('entities', 'EntityController')
        ->only(['index', 'show']);

    Route::get('/all/entities', 'EntityController@all');

    Route::apiResource('news_categories', 'NewsCategoryController')
        ->only(['index', 'show']);

    Route::get('/news/recommend', 'NewsController@recommend');
    Route::get('/news/other', 'NewsController@other');
    Route::apiResource('news', 'NewsController')
        ->only(['index', 'show']);

    Route::apiResource('help_categories', 'HelpCategoryController')
        ->only(['index', 'show']);

    Route::apiResource('helps', 'HelpController')
        ->only(['index', 'show']);

    Route::apiResource('scenes', 'SceneController')
        ->only(['index', 'show']);

    Route::apiResource('activities', 'ActivityController')
        ->only(['index', 'show']);

    Route::apiResource('links', 'LinkController')
        ->only(['index', 'show']);

    Route::apiResource('partners', 'PartnerController')
        ->only(['index', 'show']);

    Route::apiResource('expresses', 'ExpressController')
        ->only(['index', 'show']);

    Route::apiResource('free_expresses', 'FreeExpressController')
        ->only(['index']);

    Route::apiResource('coupons', 'CouponController')
        ->only(['index', 'show']);

    Route::apiResource('hot_keywords', 'HotKeywordController')
        ->only(['index']);

    Route::apiResource('combinations', 'CombinationController')
        ->only(['index']);

    Route::apiResource('member_levels', 'MemberLevelController')
        ->only(['index']);

    Route::apiResource('accumulate_points_money', 'AccumulatePointsMoneyController')
        ->only(['index']);

    Route::apiResource('gifts', 'GiftController')
        ->only(['index', 'show']);

    Route::apiResource('banners', 'BannerController')
        ->only(['index']);

    Route::apiResource('recommend_new_entities', 'RecommendNewEntityController')
        ->only(['index']);

    Route::apiResource('recommend_entities', 'RecommendEntityController')
        ->only(['index']);

    Route::apiResource('recommend_news', 'RecommendNewsController')
        ->only(['index']);

    Route::get('/new_orders', 'OrderController@newOrder');

    Route::get('/navigation/entities', 'EntityController@navigation');

    Route::get('/more/cards', 'EntityController@more');

    Route::get('/maps', 'MapController@index');

    Route::get('/receive_record', 'UserCouponController@record');

    /**
     * 需登录后访问
     */
    Route::middleware('token')->group(function () {
        // 绑定登录方式
        Route::post('/login_modes', 'TokenController@bindLoginMode');
        // 解绑登录方式
        Route::delete('/login_modes', 'TokenController@unbindLoginMode');
        // 获取拥有的组
        Route::get('/users/self/roles', 'UserController@roles');
        // 获取拥有的权限
        Route::get('/users/self/permissions', 'UserController@permissions');
        // 我的消息
        Route::get('/users/self/messages', 'UserController@messages');
        // 未读消息条数
        Route::get('/users/self/messages/unread_count', 'UserController@unreadMessageCount');
        // 我的评价
        Route::get('/users/self/comments', 'UserController@comments');
        // 收货地址
        Route::get('/users/self/addresses', 'UserController@addresses');
        // 积分记录
        Route::get('/users/self/accumulate_points_records', 'UserController@accumulatePointsRecords');
        // 资产记录
        Route::get('/users/self/balance_records', 'UserController@balanceRecords');
        // 充值订单
        Route::get('/users/self/recharge_orders', 'UserController@rechargeOrders');
        // 礼品订单
        Route::get('/users/self/gift_orders', 'UserController@giftOrders');
        // 我的购物车
        Route::get('/users/self/carts', 'UserController@carts');
        // 商品订单
        Route::get('/users/self/orders', 'UserController@orders');
        // 我的关注
        Route::get('/users/self/follows', 'UserController@follows');
        // 我的发票
        Route::get('/users/self/receipts', 'UserController@receipts');
        // 获取我的优惠券
        Route::get('/users/self/coupons', 'UserController@coupons');
        // 领取优惠券
        Route::apiResource('user_coupons', 'UserCouponController')
            ->only(['store']);

        Route::apiResource('follows', 'FollowController')
            ->only(['store']);
        Route::delete('/follows', 'FollowController@destroy');

        Route::apiResource('carts', 'CartController')
            ->only(['store', 'destroy']);
        Route::post('/batch/carts', 'CartController@batchStore');
        Route::delete('/batch/carts', 'CartController@batchDestroy');

        Route::apiResource('addresses', 'AddressController')
            ->only(['index', 'show', 'store', 'update', 'destroy']);

        Route::apiResource('gift_orders', 'GiftOrderController')
            ->only(['show', 'store']);

        Route::apiResource('orders', 'OrderController')
            ->only(['show', 'store', 'update']);

        Route::apiResource('receipts', 'ReceiptController')
            ->only(['show', 'store']);

        Route::apiResource('comments', 'CommentController')
            ->only(['store']);

        Route::apiResource('messages', 'MessageController')
            ->only(['show', 'update', 'destroy']);

        Route::apiResource('recharge_orders', 'RechargeOrderController')
            ->only(['show']);

        Route::prefix('batch')->group(function () {
            Route::delete('/messages', 'MessageController@batchDestroy');
            Route::put('/messages', 'MessageController@batchUpdate');
        });

        Route::apiResource('statistics', 'StatisticController')
            ->only(['index']);

        Route::post('/balance/pay', 'BalancePayController@pay');

        Route::get('/alipay/recharge', 'AliPayController@recharge');
        Route::get('/alipay/pay', 'AliPayController@pay');
        Route::post('/wxpay/recharge', 'WxPayController@recharge');
        Route::post('/wxpay/pay', 'WxPayController@pay');
    });

    /**
     * 需超级管理员权限
     */
    Route::middleware('role:super')->group(function () {
        Route::apiResource('systems', 'SystemSettingController')
            ->only(['index', 'store']);

        Route::apiResource('logs', 'LogController')
            ->only(['index', 'destroy']);

        Route::get('/export/logs', 'LogController@export');

        // 角色
        Route::apiResource('roles', 'RoleController')
            ->only(['index']);

        Route::apiResource('permissions', 'PermissionController')
            ->only(['index']);
        // 管理员账号
        Route::apiResource('admins', 'AdminAccountController');

        // 批量管理
        Route::prefix('batch')->group(function () {
            // 批量删除管理员账号
            Route::delete('/admins', 'AdminAccountController@batchDestroy');
            // 批量删除日志
            Route::delete('/logs', 'LogController@batchDestroy');
        });
    });

    Route::middleware('permission:搜索管理')->group(function () {
        Route::apiResource('hot_keywords', 'HotKeywordController')
            ->only(['store', 'update', 'destroy']);

        Route::prefix('batch')->group(function () {
            Route::delete('/hot_keywords', 'HotKeywordController@batchDestroy');
        });
    });

    Route::middleware('permission:首页推荐管理')->group(function () {
        Route::apiResource('links', 'LinkController')
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('partners', 'PartnerController')
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('banners', 'BannerController')
            ->only(['update']);

        Route::apiResource('recommend_new_entities', 'RecommendNewEntityController')
            ->only(['update']);

        Route::apiResource('recommend_entities', 'RecommendEntityController')
            ->only(['update']);

        Route::apiResource('recommend_news', 'RecommendNewsController')
            ->only(['update']);

        Route::apiResource('recommend_others', 'RecommendOtherController')
            ->only(['index', 'update']);

        Route::get('/auto_recommend', 'RecommendOtherController@getAutoRecommend');
        Route::put('/auto_recommend', 'RecommendOtherController@autoRecommend');

        Route::prefix('batch')->group(function () {
            Route::delete('/links', 'LinkController@batchDestroy');
            Route::delete('/partners', 'PartnerController@batchDestroy');
        });
    });

    Route::middleware('permission:新闻管理')->group(function () {
        Route::apiResource('news', 'NewsController')
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('news_categories', 'NewsCategoryController')
            ->only(['update']);

        Route::prefix('batch')->group(function () {
            Route::delete('/news', 'NewsController@batchDestroy');
        });
    });

    Route::middleware('permission:帮助中心管理')->group(function () {
        Route::apiResource('help_categories', 'HelpCategoryController')
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('helps', 'HelpController')
            ->only(['store', 'update', 'destroy']);

        // 批量管理
        Route::prefix('batch')->group(function () {
            Route::delete('/help_categories', 'HelpCategoryController@batchDestroy');
            Route::delete('/helps', 'HelpController@batchDestroy');
        });
    });

    Route::middleware('permission:活动管理')->group(function () {
        Route::apiResource('activities', 'ActivityController')
            ->only(['store', 'update', 'destroy']);
    });

    Route::middleware('permission:场景用途管理')->group(function () {
        Route::apiResource('scenes', 'SceneController')
            ->only(['update']);
        Route::apiResource('scene_categories', 'SceneCategoryController')
            ->only(['index', 'show']);
        Route::apiResource('scene_goods', 'SceneGoodController')
            ->only(['show', 'store', 'update', 'destroy']);
        Route::delete('/batch/scene_goods', 'SceneGoodController@batchDestroy');
    });

    Route::middleware('permission:商品管理')->group(function () {
        Route::apiResource('large_categories', 'LargeCategoryController')
            ->only(['update']);

        Route::apiResource('large_category_items', 'LargeCategoryItemController')
            ->only(['index', 'store', 'update']);
        Route::delete('large_category_items', 'LargeCategoryItemController@delete');

        Route::apiResource('categories', 'CategoryController')
            ->only(['index', 'store', 'update', 'destroy']);

        Route::apiResource('category_items', 'CategoryItemController')
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('types', 'TypeController')
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('secondary_types', 'SecondaryTypeController')
            ->only(['index', 'store', 'update', 'destroy']);

        // 商品
        Route::apiResource('entities', 'EntityController')
            ->only(['store', 'update', 'destroy']);

        // 修改组合价格
        Route::apiResource('combinations', 'CombinationController')
            ->only(['update']);

        // 导出组合
        Route::get('/export/combinations', 'CombinationController@export');
        // 导入组合
        Route::post('/import/combinations', 'CombinationController@import');
    });

    Route::middleware('permission:订单管理')->group(function () {
        Route::put('/back_pay', 'OrderController@backPay');

        Route::apiResource('orders', 'OrderController')
            ->only(['index']);

        Route::get('/export/orders', 'OrderController@export');

        Route::get('order_status', 'OrderController@statusList');

        Route::apiResource('order_expresses', 'OrderExpressController')
            ->only(['index', 'store']);

        Route::apiResource('order_applies', 'OrderApplyController')
            ->only(['store']);

        Route::get('/all/users', 'UserController@all');

        Route::apiResource('shopping_bill', 'ShoppingController')
            ->only(['index']);
    });

    Route::middleware('permission:积分管理')->group(function () {
        Route::apiResource('member_levels', 'MemberLevelController')
            ->only(['store', 'update']);

        Route::put('/accumulate_points_money', 'AccumulatePointsMoneyController@update');

        Route::apiResource('gift_orders', 'GiftOrderController')
            ->only(['index', 'update']);

        Route::apiResource('gifts', 'GiftController')
            ->only(['store', 'update', 'destroy']);

        Route::delete('/batch/gifts', 'GiftController@batchDestroy');
    });

    Route::middleware('permission:用户管理')->group(function () {
        Route::apiResource('users', 'UserController')
            ->only(['index', 'update', 'destroy']);

        Route::apiResource('messages', 'MessageController')
            ->only(['index', 'store']);
    });

    Route::middleware('permission:客服工具管理')->group(function () {
        Route::post('/back_orders', 'OrderController@backOrder');
    });

    Route::middleware('permission:优惠券管理')->group(function () {
        Route::post('/coupons/give', 'CouponController@give');

        Route::apiResource('coupons', 'CouponController')
            ->only(['store', 'update', 'destroy']);
    });

    Route::middleware('permission:财务管理')->group(function () {
        Route::get('/statistic/finances', 'FinanceStatisticController@statistic');
        Route::apiResource('finances', 'FinanceStatisticController')
            ->only(['index']);

        Route::get('/export/finances', 'FinanceStatisticController@export');
        Route::get('/export/recharge_orders', 'RechargeOrderController@export');

        Route::apiResource('receipts', 'ReceiptController')
            ->only(['index', 'update']);
        Route::put('/batch/receipts', 'ReceiptController@batchReceipted');

        Route::apiResource('recharge_orders', 'RechargeOrderController')
            ->only(['index']);

        Route::apiResource('order_applies', 'OrderApplyController')
            ->only(['index', 'update']);
    });

    Route::middleware('permission:配送管理')->group(function () {
        Route::apiResource('expresses', 'ExpressController')
            ->only(['store', 'update', 'destroy']);

        Route::put('/free_expresses', 'FreeExpressController@update');
    });

    Route::middleware('permission:数据中心')->group(function () {
        Route::get('/type_percent', 'OperateStatisticController@typePercent');
        Route::get('/operate/statistics', 'OperateStatisticController@statistics');
    });

    Route::get('/test', function () {
        return (new \App\Services\KDN())->generate()['PrintTemplate'];

        \App\Jobs\OrderExpire::dispatch(\App\Models\Order::find(1));

        return '';

        return \Carbon\Carbon::now()->addSeconds(5)->diffForHumans();

        return request()->getClientIp();
        return Mail::send(new \App\Mail\OrderPaid(\App\Models\Order::find(1)));
        return \Carbon\Carbon::parse(date('Y-m-d H:i:s', '1537654321'));

        return \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(500)->generate('111');
        return [
            'data' => true || \App\Services\Tokens\TokenFactory::can('用户管理')
        ];
        return \App\Services\Tokens\TokenFactory::bindEmail('392113643@qq.com', function ($password) {
            // TODO 发送密码

        });
        return (new \App\Http\Controllers\Api\AliPayController())->pay();
        return \App\Models\MemberLevel::find(5)->users()->pluck('id');

        $array = array_merge_recursive([1, 2, 3], [5, 1, 4]);
        return array_flip($array);

        $order = [
            'out_trade_no' => time(),
            'body' => 'subject-测试',
            'total_fee' => '1',
            'openid' => 'onkVf1FjWS5SBIixxxxxxxxx',
        ];
        $result = \Yansongda\LaravelPay\Facades\Pay::wechat()->scan($order);
        return $order;
        preg_match_all('/\d+/', '10盒', $arr);
        return $arr[0][0];
        return uuid();
        \App\Models\AccumulatePointsRecord::income(1000, '收入');
        return \App\Services\Tokens\TokenFactory::getCurrentUser()->accumulate_points;

        return config('setting.accumulate_points_money');
        Cache::forget(Cache::pull('haha'));

//        Cache::add('haha', '123', 10);
        return Cache::get('haha');

        return 111;

        return [
            'data' => \App\Services\SMS::sendSms('18656986662', '158960')
        ];

        return isset(request()->is_admin) ? request()->is_admin : 11;

        return \Spatie\Permission\Models\Role::findOrFail(1)
            ->permissions()->pluck('name');

        return \App\Services\Tokens\TokenFactory::getCurrentRoles();

        return \App\Services\Tokens\TokenFactory::getCurrentPermissions();

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\EntityCombinationsExport(1), 'combinations.xlsx');

        return \App\Models\Attribute::where('entity_id', 1)
            ->pluck('name')
            ->toArray();

        $combination = '铜版纸|20*20|5|镶金';
        return explode('|', $combination);

        // 组合价格
        $price = [
            [
                'collection' => ['数量' => 1, '尺寸' => '20*20', '材质' => '铜版纸'],
                'price' => 100
            ],
            [
                'collection' => ['数量' => 2, '尺寸' => '20*20', '材质' => '铜版纸'],
                'price' => 200
            ],
            [
                'collection' => ['数量' => 3, '尺寸' => '20*20', '材质' => '铜版纸'],
                'price' => 300
            ],
            [
                'collection' => ['数量' => 4, '尺寸' => '20*20', '材质' => '铜版纸'],
                'price' => 400
            ],
            [
                'collection' => ['数量' => 5, '尺寸' => '20*20', '材质' => '铜版纸'],
                'price' => 500
            ],
        ];
        foreach ($price as $item) {
            if ($item['collection'] == ['数量' => 3, '尺寸' => '20*20', '材质' => '铜版纸']) {
                return $item['price'];
            }
        }
    });
});