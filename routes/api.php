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

    // 发送短信
    Route::post('/sms', 'SmsController@sendSms')->middleware('throttle:2,1');

    // 图片上传
    Route::apiResource('images', 'ImageController')
        ->only(['store']);
    // 文件上传
    Route::apiResource('files', 'FileController')
        ->only(['store']);

    Route::apiResource('users', 'UserController')
        ->only(['show']);

    Route::apiResource('large_categories', 'LargeCategoryController')
        ->only(['index', 'show']);

    Route::apiResource('categories', 'CategoryController')
        ->only(['show']);

    Route::apiResource('types', 'TypeController')
        ->only(['index', 'show']);

    Route::apiResource('entities', 'EntityController')
        ->only(['index', 'show']);

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

    /**
     * 需登录后访问
     */
    Route::middleware('token')->group(function () {
        // 刷新token
        Route::post('/refresh', 'TokenController@refresh')->name('tokens.refresh');
        // 修改密码
        Route::put('/repassword', 'TokenController@rePassword')->name('token.rePassword');
        // 获取自己的资料
        Route::get('/users/self', 'UserController@self')->name('users.self');
        // 我的消息
        Route::get('/users/self/messages', 'UserController@messages');
        // 未读消息条数
        Route::get('/users/self/messages/unread_count', 'UserController@unreadMessageCount');
        // 收货地址
        Route::get('/users/self/addresses', 'UserController@addresses');
        // 积分记录
        Route::get('/users/self/accumulate_points_records', 'UserController@accumulatePointsRecords');
        // 礼品订单
        Route::get('/users/self/gift_orders', 'UserController@giftOrders');
        // 我的购物车
        Route::get('/users/self/carts', 'UserController@carts');
        // 商品订单
        Route::get('/users/self/orders', 'UserController@orders');
        // 我的发票
        Route::get('/users/self/receipts', 'UserController@receipts');
        // 获取我的优惠券
        Route::get('/users/self/coupons', 'UserController@coupons');
        // 领取优惠券
        Route::apiResource('user_coupons', 'UserCouponController')
            ->only(['store']);

        Route::apiResource('carts', 'CartController')
            ->only(['store', 'delete']);
        Route::post('/batch/carts', 'CartController@batchStore');
        Route::delete('/batch/carts', 'CartController@batchDestroy');

        Route::apiResource('addresses', 'AddressController')
            ->only(['index', 'show', 'store', 'update', 'delete']);

        Route::apiResource('gift_orders', 'GiftOrderController')
            ->only(['show', 'store']);

        Route::apiResource('orders', 'OrderController')
            ->only(['show', 'store']);

        Route::apiResource('receipts', 'ReceiptController')
            ->only(['show', 'store']);

        Route::apiResource('users', 'UserController')
            ->only(['update']);

        Route::apiResource('comments', 'CommentController')
            ->only(['store']);

        Route::apiResource('messages', 'MessageController')
            ->only(['show', 'update', 'destroy']);

        Route::prefix('batch')->group(function () {
            Route::delete('/messages', 'MessageController@batchDestroy');
            Route::put('/messages', 'MessageController@batchUpdate');
        });
    });

    /**
     * 需超级管理员权限
     */
    Route::middleware('role:super')->group(function () {
        // 角色
        Route::apiResource('roles', 'RoleController')
            ->only(['index']);
        // 管理员账号
        Route::apiResource('admins', 'AdminAccountController');

        // 批量管理
        Route::prefix('batch')->group(function () {
            // 批量删除管理员账号
            Route::delete('/admins', 'AdminAccountController@batchDestroy');
        });
    });

    Route::middleware('permission:搜索管理')->group(function () {
        Route::apiResource('hot_keywords', 'HotKeywordController')
            ->only(['store', 'update', 'destroy']);
    });

    Route::middleware('permission:首页推荐管理')->group(function () {
        Route::apiResource('links', 'LinkController')
            ->only(['store', 'update', 'destroy']);

        Route::apiResource('partners', 'PartnerController')
            ->only(['store', 'update', 'destroy']);
    });

    Route::middleware('permission:新闻管理')->group(function () {
        Route::apiResource('news', 'NewsController')
            ->only(['store', 'update', 'destroy']);

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
            ->only(['store', 'update', 'destroy']);
        Route::apiResource('scene_categories', 'SceneCategoryController')
            ->only(['update', 'destroy']);
        Route::apiResource('scene_goods', 'SceneGoodController')
            ->only(['update', 'destroy']);
    });

    Route::middleware('permission:商品管理')->group(function () {
        Route::apiResource('large_categories', 'LargeCategoryController')
            ->only(['update']);

        Route::apiResource('categories', 'CategoryController')
            ->only(['store', 'update', 'destroy']);

        // 商品
        Route::apiResource('entities', 'EntityController')
            ->only(['store', 'update']);

        // 修改组合价格
        Route::apiResource('combinations', 'CombinationController')
            ->only(['update']);

        // 导出组合
        Route::get('combinations/export', 'CombinationController@export');
        // 导入组合
        Route::post('combinations/import', 'CombinationController@import');
    });

    Route::middleware('permission:订单管理')->group(function () {
        Route::apiResource('gift_orders', 'GiftOrderController')
            ->only(['index', 'update']);

        Route::put('/orders/back_pay', 'OrderController@backPay');

        Route::apiResource('orders', 'OrderController')
            ->only(['index', 'update']);

        Route::apiResource('order_expresses', 'OrderExpressController')
            ->only(['store']);

        Route::apiResource('receipts', 'ReceiptController')
            ->only(['index', 'update']);
    });

    Route::middleware('permission:积分管理')->group(function () {
        Route::apiResource('member_levels', 'MemberLevelController')
            ->only(['store', 'update']);

        Route::put('/accumulate_points_money', 'AccumulatePointsMoneyController@update');

        Route::apiResource('gifts', 'GiftController')
            ->only(['store', 'update', 'destroy']);
    });

    Route::middleware('permission:用户管理')->group(function () {
        Route::apiResource('users', 'UserController')
            ->only(['index', 'destroy']);

        Route::apiResource('messages', 'MessageController')
            ->only(['index', 'store']);
    });

    Route::middleware('permission:客服工具管理')->group(function () {

    });

    Route::middleware('permission:优惠券管理')->group(function () {
        Route::apiResource('coupons', 'CouponController')
            ->only(['store', 'update', 'destroy']);
    });

    Route::middleware('permission:财务管理')->group(function () {

    });

    Route::middleware('permission:配送管理')->group(function () {
        Route::apiResource('expresses', 'ExpressController')
            ->only(['store', 'update', 'destroy']);

        Route::put('/free_expresses', 'FreeExpressController@update');
    });

    Route::middleware('permission:数据中心')->group(function () {

    });


    Route::get('/test', function () {
        return \App\Models\MemberLevel::find(5)->users()->pluck('id');

        $array = array_merge_recursive([1, 2, 3], [5, 1, 4]);
        return array_flip($array);

        $order = [
            'out_trade_no' => time(),
            'body' => 'subject-测试',
            'total_fee'      => '1',
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

        return \Carbon\Carbon::parse(date('Y-m-d H:i:s', '1537654321'));

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