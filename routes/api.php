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

    // 获取自己的资料
    Route::get('/users/self', 'UserController@self')->name('users.self');
    // 获取我的优惠券
    Route::get('/users/self/coupons', 'UserController@coupons');

    Route::apiResource('images', 'ImageController')
        ->only(['store']);

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
        ->only(['show']);

    Route::apiResource('coupons', 'CouponController')
        ->only(['index', 'show']);
    Route::post('/coupons/receive', 'CouponController@receive');

    Route::apiResource('hot_keywords', 'HotKeywordController')
        ->only(['index']);

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
        // 商品
        Route::apiResource('entities', 'EntityController')
            ->only(['store']);
    });

    Route::middleware('permission:订单管理')->group(function () {

    });

    Route::middleware('permission:积分管理')->group(function () {

    });

    Route::middleware('permission:用户管理')->group(function () {

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

        Route::apiResource('free_expresses', 'FreeExpressController')
            ->only(['update']);
    });

    Route::middleware('permission:数据中心')->group(function () {

    });


    Route::get('/test', function () {

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