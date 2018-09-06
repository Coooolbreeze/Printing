# 易特印API文档

### 说明
* 参数前带 ^ 为非必填字段或非必返回字段

### 公共请求参数
    base_url: http://printing.besthtml5.com/api
    header:
    {
        token: 登录或注册接口返回的access_token
    }

### 数据返回格式
    {
        status: 请求状态 (success 或者 error),
        code: 状态码 (
            200 请求成功，
            201 资源创建成功，
            400 请求失败，
            401 未登录或登录已过期，
            403 权限不足，
            404 请求资源未找到，
            500 服务器故障
        )，
        ^error_code: 错误码，当status为error时返回 (
            10003 权限不足，
            20000 账号不存在，
            20001 密码错误，
            20002 账号已被注册，
            20003 注册失败，
            20005 修改密码失败，
            20006 验证码错误，
            20007 账号格式错误，
              999 其他错误，具体错误信息请查看message字段
        ),
        ^message: 提示信息，当status为error或者本次操作无数据返回时(如资源创建、更新、删除等操作),
        ^data: 请求到的数据，下列所有response如无特殊说明都是指这里面的数据
    }
    
### 分页参数
    request:
    {
        ^page: 请求页数,默认1,
        ^limit: 每页显示的条数,默认15
    }
    response:
    {
        data: 返回的数据,下列所有response中的data参数如无特殊说明都是指这里面的数据，其余分页参数不再赘述
        count: 当前页数据条数,
        total: 总数据条数,
        current_page: 当前页数,
        last_page: 最后页码，即总页数,
        has_more_pages: 是否还有下一页
    }
    
------

### 1. 图片上传
    POST /images
    response:
    [
        {
            id: 图片ID，
            src: 图片链接
        },
        ...
    ]
    
### 2. 文件上传
    POST /files
    response:
    [
        {
            id: 文件ID，
            name: 文件名,
            src: 文件链接
        },
        ...
    ]

### 3. 获取短信验证码
    POST /sms (同个IP每分钟只能请求一次)
    request:
    {
        phone: 手机号,
        ^is_register: true (注册或绑定时需加上此字段)
    }
    response:
    {
        verification_token: 验证令牌
    }
    
### 4. 获取邮箱验证码（暂未实现）
    POST /mail
    request:
    {
        email: 邮箱地址,
        ^is_register: true (注册或绑定时需加上此字段)
    }
    response:
    {
        verification_token: 验证令牌
    }
    
### 5. 注册
    POST /register
    request:
    {
        verification_code: 验证码，
        verification_token: 获取验证码接口返回的verification_token,
        password: 密码
    }
    response:
    {
        access_token: 令牌,
        refresh_token: 刷新令牌,可在令牌过期后进行刷新,过期时间为access_token_expire * 10
        access_token_expire:令牌有效期 单位秒,
    }

### 6. 验证码登录
    POST /login
    request:
    {
        verification_code: 验证码，
        verification_token: 获取验证码接口返回的verification_token
    }
    response:
    {
        access_token: 令牌,
        refresh_token: 刷新令牌,可在令牌过期后进行刷新,过期时间为access_token_expire * 10
        access_token_expire:令牌有效期 单位秒,
    }
    
### 7. 账号密码登录
    POST /login
    request:
    {
        username: 用户名,
        password: 密码,
        ^is_admin: true (后台管理系统登录需加上此字段)
    }
    response:
    {
        access_token: 令牌,
        refresh_token: 刷新令牌,可在令牌过期后进行刷新,过期时间为access_token_expire * 10
        access_token_expire:令牌有效期 单位秒
    }
    
### 8. 刷新令牌
    POST /refresh
    header:
    {
        token: 登录或注册接口返回的refresh_token
    }
    response:
    {
        access_token: 令牌,
        refresh_token: 刷新令牌,可在令牌过期后进行刷新,过期时间为access_token_expire * 10
        access_token_expire:令牌有效期 单位秒
    }
    
### 9. 修改密码
    PUT /repassword
    request:
    {
        verification_code: 验证码，
        verification_token: 获取验证码接口返回的verification_token,
        password: 密码
    }
    
### 10. 微信登录（暂未实现）
    POST /wechat/login

### 11. 绑定登录方式
    POST /login_modes
    request:
    {
        verification_code: 短信或邮箱验证码，
        verification_token: 短信或邮箱验证码接口返回的verification_token
    }
    
### 13. 解绑登录方式
    DELETE /login_modes
    request:
    {
        type: 需解绑的登录方式(phone|email)
    }
    
### 14. 获取自己的资料
    GET /users/self
    response:
    {
        id: 用户ID,
        nickname: 昵称,
        avatar: 头像地址,
        sex: 性别,
        account: 账号,
        phone: 手机号,
        email: 邮箱,
        member_level: {
            id: 会员等级ID，
            icon: {
                id: 会员图标ID,
                src: 会员图标链接
            },
            name: 会员等级名称,
            accumulate_points: 当前等级所需积分,
            discount: 当前等级折扣
        },
        accumulate_points: 当前剩余积分,
        history_accumulate_points: 历史总积分,
        balance: 账户余额,
        consume: 总消费,
        order_unpaid_count: 待付款订单数量,
        order_undelivered_count: 待印刷订单数量,
        order_delivered_count: 配送中订单数量,
        order_received_count: 待评价订单数量,
        coupon_count: 可用优惠券数量,
        is_bind_account: 是否已绑定账号,
        is_bind_phone: 是否已绑定手机号,
        is_bind_email: 是否已绑定邮箱,
        is_bind_wx: 是否已绑定微信,
        created_at: 注册日期
    }
    
### 15. 更新自己的资料
    PUT /users/self
    request:
    {
        ^nickname: 昵称,
        ^sex: 性别 (
            0 未知,
            1 男,
            2 女
        ),
        ^avatar: 头像地址
    }
    
### 16. 获取拥有的权限列表
    GET /users/self/permissions
    response:
    [
        "搜索管理",
        "首页推荐管理",
        "新闻管理",
        "帮助中心管理",
        "活动管理",
        "场景用途管理",
        "商品管理",
        "订单管理",
        "积分管理",
        "用户管理",
        "客服工具管理",
        "优惠券管理",
        "财务管理",
        "配送管理",
        "数据中心"
    ]
    
### 17. 获取我的站内信列表
    GET /users/self/messages
    request:
    {
        ^is_read: 筛选已读或未读消息 (
            0 未读消息,
            1 已读消息
        ),
    }
    response:
    {
        data:
        [
            {
                id: 站内信ID,
                user: {
                    id: 用户ID,
                    nickname: 用户昵称
                },
                sender: 发信者,
                title: 标题,
                body: 内容,
                is_read: 是否已读,
                created_at: 发信日期
            }，
            ...
        ]
    }
    
### 18. 获取我的未读消息数
    GET /users/self/messages/unread_count
    response:
    {
        count: 未读消息数量
    }
    
### 19. 获取我的评价列表
    GET /users/self/comments
    response:
    {
        data:
        [
            {
                id: 该评价ID,
                ^user: {
                    id: 用户ID,
                    nickname: 用户昵称,
                    avatar: 用户头像
                },
                goods: {
                    id: 评价的商品ID,
                    name: 评价的商品名称
                },
                ^target: 评价的商品属性,
                goods_comment: 商品评价,
                service_comment: 服务评价,
                images:
                [
                    {
                        id: 图片ID，
                        src: 图片链接
                    },
                    ...
                ],
                describe_grade: 描述相符分数,
                seller_grade: 卖家服务分数,
                logistics_grade: 物流服务分数,
                is_anonymous: 是否匿名评价,匿名时不返回user字段,
                created_at: 评价日期
            }
        ]
    }
    
### 20. 获取我的优惠券列表
    GET /users/self/coupons
    request:
    {
        ^type: 筛选优惠券状态 (
            1 可用,
            2 已使用,
            3 已过期
        )
    }
    response:
    {
        data:
        [
            {
                id: 优惠券ID,
                coupon_no: 优惠券编号,
                name: 优惠券名称,
                type: 优惠券类型 (满减|抵扣),
                quota: 优惠券金额,
                ^satisfy: 需满足金额，type为满减时返回,
                status: 优惠券状态 (已使用|已过期|可使用),
                finished_at: 过期时间,
                created_at: 领取时间
            },
            ...
        ]
    }
    
### 21. 获取我的积分记录
    GET /users/self/accumulate_points_records
    request:
    {
        ^type: 筛选积分记录 (
            1 收入记录,
            2 支出记录
        )
    }
    response:
    {
        data:
        [
            {
                id: 记录ID,
                type: 类型 (收入|支出),
                number: 数量,
                surplus: 剩余数量,
                describe: 说明,
                created_at: 操作日期
            },
            ...
        ]
    }
    
### 22. 获取我的资产记录
    GET /users/self/balance_records
    request:
    {
        ^type: 筛选资产记录 (
            1 收入记录,
            2 支出记录
        )
    }
    response:
    {
        data:
        [
            {
                id: 记录ID,
                type: 类型 (收入|支出),
                number: 数量,
                surplus: 剩余数量,
                describe: 说明,
                ^order_no: type为支出时返回,
                created_at: 操作日期
            },
            ...
        ]
    }
    
### 23. 获取我的收货地址
    GET /users/self/addresses
    request:
    {
        ^is_default: true (只获取默认地址就加上)
    }
    response:
    [
        {
            id: 地址ID,
            name: 收货人姓名,
            phone: 收货人手机号,
            province: 省份,
            city: 市,
            county: 区/县,
            detail: 详细地址,
            is_default: 是否是默认地址
        },
        ...
    ]
    
### 24. 获取我的余额充值订单
    GET /users/self/recharge_orders
    response:
    {
        data:
        [
            {
                id: 记录ID,
                user: {
                    id: 用户ID,
                    nickname: 用户昵称
                },
                order_no: 订单号,
                price: 充值金额,
                pay_type: 充值方式 (支付宝|微信),
                created_at: 充值日期
            },
            ...
        ]
    }
    
### 25. 获取我的礼品订单
    GET /users/self/gift_orders
    request:
    {
        ^status: 筛选发货状态 (
            1 未发货,
            2 已发货
        )
    }
    response:
    {
        data:
        [
            {
                id: 订单ID,
                order_no: 订单编号,
                user: {
                    id: 用户ID,
                    nickname: 用户昵称
                },
                address: {
                    name: 收货人姓名,
                    phone: 收货人手机号,
                    province: 省份,
                    city: 市,
                    county: 区/县,
                    detail: 详细地址
                }，
                content: {
                    id: 礼品ID,
                    name: 礼品名称
                    image: {
                        id: 礼品图片ID,
                        src: 礼品图片地址
                    },
                    accumulate_points: 消耗积分
                },
                express_company: 快递公司,
                ^tracking_no: 快递单号，status为已发货时返回,
                status: 发货状态 (未发货|已发货),
                created_at: 下单日期
            },
            ...
        ]
    }
    
### 26. 获取我的商品订单
    GET /users/self/orders
    request:
    {
        ^status: 筛选订单状态(
            0 已失效
            1 待付款
            2 待审核
            3 印刷中
            4 待收货
            5 待评价
            6 已评价
            7 未通过
        )
    }
    response:
    {
        unpaid_count: 待付款订单数量,
        paid_count: 待审核订单数量,
        undelivered_count: 印刷中订单数量,
        delivered_count: 待收货订单数量,
        received_count: 待评价订单数量,
        data:
        [
            {
                id: 订单ID,
                receipt_id: 发票ID,
                order_no: 订单编号,
                user: {
                    id: 用户ID,
                    nickname: 用户昵称
                },
                title: 订单标题,
                address: {
                    name: 收货人姓名,
                    phone: 收货人手机号,
                    province: 省份,
                    city: 市,
                    county: 区/县,
                    detail: 详细地址
                },
                goods_price: 商品总金额,
                goods_count: 商品总数量,
                total_weight: 商品总重量,
                freight: 运费,
                status: 订单状态 (已失效|待支付|待审核|待发货|已发货|已收货|已评论|未通过),
                discount_amount: 优惠券、活动等抵扣金额,
                member_discount: 会员折扣金额,
                total_price: 订单总金额,
                remark: 订单备注,
                created_at: 订单创建日期,
                ^expresses: 物流信息 (订单发货后显示) {
                    id: 物流ID,
                    company: 物流公司,
                    track_no: 物流单号
                },
                ^balance_deducted: 账户余额抵扣 (订单支付后显示),
                ^pay_type: 支付方式 (订单支付后显示),
                ^paid_at: 订单支付日期 (订单支付后显示),
                ^audited_at: 订单审核日期 (订单审核通过后显示),
                ^delivered_at: 订单发货日期 (订单发货后显示),
                ^received_at: 订单收货日期 (订单收货后显示)
            },
            ...
        ]
    }
    
### 27. 获取我的购物车商品信息
    GET /users/self/carts
    reponse:
    {
        data:
        [
            {
                id: 购物车商品ID,
                entity: {
                    id: 商品ID,
                    image: {
                        id: 商品图片ID,
                        src: 商品图片链接
                    },
                    name: 商品名称,
                    lead_time: 出货周期
                },
                ^file: {
                    id: 文件ID,
                    name: 文件名称,
                    src: 文件链接
                },
                specs: {
                    商品属性(如工艺): 属性值(如烫金),
                    ...
                },
                custom_specs: {
                    自定义属性(如尺寸): {
                        单位属性(如宽): 属性值(如1),
                        ...
                    },
                    ...
                },
                count: 商品数量,
                price: 商品价格,
                weight: 商品重量,
                remark: 备注
            },
            ...
        ]
    }
    
### 28. 获取我的发票信息
    GET /users/self/receipts
    response:
    {
        data:
        [
            {
                id: 发票ID,
                user: {
                    id: 用户ID,
                    nickname: 用户昵称
                },
                order: [
                    {
                        id: 订单ID,
                        title: 订单标题
                    },
                    ...
                ],
                company: 公司,
                tax_no: 纳税号,
                contact: 联系人,
                contact_way: 联系方式,
                address: 联系地址,
                money: 发票金额,
                is_receipted: 是否已开票,
                created_at: 申请日期,
                updated_at: 开票日期
            },
            ...
        ]
    }

### 29. 获取用户列表 (需用户管理权限)
    GET /users
    request:
    {
        ^member_level_id: 按会员等级ID筛选,
        ^nickname: 用户昵称模糊搜索,
        ^phone: 手机号模糊搜索
    }
    reponse:
    {
        data:
        [
            {
                id: 用户ID,
                nickname: 昵称,
                avatar: 头像地址,
                sex: 性别,
                account: 账号,
                phone: 手机号,
                email: 邮箱,
                member_level: {
                    id: 会员等级ID，
                    name: 会员等级名称,
                    accumulate_points: 当前等级所需积分,
                    discount: 当前等级折扣
                },
                accumulate_points: 当前剩余积分,
                history_accumulate_points: 历史总积分,
                balance: 账户余额,
                is_bind_account: 是否已绑定账号,
                is_bind_phone: 是否已绑定手机号,
                is_bind_email: 是否已绑定邮箱,
                is_bind_wx: 是否已绑定微信,
                created_at: 注册日期
            },
            ...
        ]
    }
    
### 30. 查看用户信息
    GET /users/{id}
    response:
    {
        id: 用户ID,
        nickname: 昵称,
        avatar: 头像地址,
        sex: 性别,
        account: 账号,
        phone: 手机号 (非管理员隐藏4-7位),
        email: 邮箱 (非管理员隐藏2-5位),
        member_level: {
            id: 会员等级ID，
            name: 会员等级名称,
            accumulate_points: 当前等级所需积分,
            discount: 当前等级折扣
        },
        accumulate_points: 当前剩余积分,
        history_accumulate_points: 历史总积分,
        ^balance: 账户余额 (非管理员不返回),
        is_bind_account: 是否已绑定账号,
        is_bind_phone: 是否已绑定手机号,
        is_bind_email: 是否已绑定邮箱,
        is_bind_wx: 是否已绑定微信,
        created_at: 注册日期
    }
    
### 31. 更新用户信息 (需用户管理权限)
    PUT /users/{id}
    request:
    {
        ^accumulate_points: 更新用户积分,
        ^balance: 更新用户余额
    }
    
### 32. 删除用户 (需用户管理权限)
    DELETE /users/{id}
    
### 33. 添加商品到购物车
    POST /carts
    request:
    {
        entity_id: 商品ID,
        price: 商品总价格,
        combination_id: 商品组合ID,
        specs: {
            商品属性(如工艺): 属性值(如烫金),
            ...
        },
        ^custom_specs: {
            自定义属性(如尺寸): {
                单位属性(如宽): 属性值(如1),
                ...
            },
            ...
        },
        ^file_id: 文件ID,
        ^count: 总数量，custom_number不为0时需传
        remark: 备注 (多人数量信息，如 张三:10盒;李四:20盒;)
    }
    
### 34. 批量添加商品到购物车,用于用户登录后上传本地缓存中的购物车数据
    POST /batch/carts
    request:
    {
        carts:
        [
            {
                  entity_id: 商品ID,
                  price: 商品总价格,
                  combination_id: 商品组合ID,
                  specs: {
                      商品属性(如工艺): 属性值(如烫金),
                      ...
                  },
                  ^custom_specs: {
                      自定义属性(如尺寸): {
                          单位属性(如宽): 属性值(如1),
                          ...
                      },
                      ...
                  },
                  ^file_id: 文件ID,
                  ^count: 总数量，custom_number不为0时需传
                  remark: 备注 (多人数量信息，如 张三:10盒;李四:20盒;)
            },
            ...
        ]
    }
    
### 35. 删除购物车商品
    DELETE /carts/{id}
    
### 36. 批量删除购物车商品
    DELETE /batch/carts
    request:
    {
        ids: 购物车商品ID数组，如[1,2,3]
    }
    
### 37. 创建商品订单
    POST /orders
    request:
    {
        ^ids: 购物车商品ID数组，如[1,2,3]，购物车内下单需传,
        ^entity: 直接下单需传 {
            entity_id: 商品ID,
            price: 商品总价格,
            combination_id: 商品组合ID,
            specs: {
                商品属性(如工艺): 属性值(如烫金),
                ...
            },
            ^custom_specs: {
                自定义属性(如尺寸): {
                    单位属性(如宽): 属性值(如1),
                    ...
                },
                ...
            },
            ^file_id: 文件ID,
            ^count: 总数量，custom_number不为0时需传
            remark: 备注 (多人数量信息，如 张三:10盒;李四:20盒;)
        },
        ^receipt_info: 开票信息 {
            company: 公司,
            tax_no: 纳税号,
            contact: 联系人,
            contact_way: 联系方式,
            address: 地址
        },
        ^address_id: 收货地址ID,不传时使用用户默认地址 (如果有),
        express_id: 快递公司ID,
        remark: 订单备注
    }
    
### 38. 获取商品订单列表 (需订单管理权限)
    GET /orders
    request:
    {
        ^status: 筛选订单状态(
            0 已失效
            1 未支付
            2 已支付
            3 待发货
            4 已发货
            5 已收货
            6 已评论
        )
    }
    response:
    {
        data:
        [
            {
                id: 订单ID,
                receipt_id: 发票ID,
                order_no: 订单编号,
                user: {
                    id: 用户ID,
                    nickname: 用户昵称
                },
                title: 订单标题,
                address: {
                    name: 收货人姓名,
                    phone: 收货人手机号,
                    province: 省份,
                    city: 市,
                    county: 区/县,
                    detail: 详细地址
                },
                goods_price: 商品总金额,
                goods_count: 商品总数量,
                total_weight: 商品总重量,
                freight: 运费,
                status: 订单状态 (已失效|待支付|已支付|待发货|已发货|已收货|已评论),
                discount_amount: 优惠券、活动等抵扣金额,
                member_discount: 会员折扣金额,
                total_price: 订单总金额,
                remark: 订单备注,
                created_at: 订单创建日期,
                ^expresses: 物流信息 (订单发货后显示) {
                    id: 物流ID,
                    company: 物流公司,
                    track_no: 物流单号
                },
                ^balance_deducted: 账户余额抵扣 (订单支付后显示),
                ^pay_type: 支付方式 (订单支付后显示),
                ^paid_at: 订单支付日期 (订单支付后显示),
                ^audited_at: 订单审核日期 (订单审核通过后显示),
                ^delivered_at: 订单发货日期 (订单发货后显示),
                ^received_at: 订单收货日期 (订单收货后显示)
            },
            ...
        ]
    }

### 39. 查看商品订单详情 (需订单管理权限或者自己)
    GET /orders/{id}
    response:
    {
        id: 订单ID,
        receipt_id: 发票ID,
        order_no: 订单编号,
        user: {
            id: 用户ID,
            nickname: 用户昵称
        },
        title: 订单标题,
        address: {
            name: 收货人姓名,
            phone: 收货人手机号,
            province: 省份,
            city: 市,
            county: 区/县,
            detail: 详细地址
        },
        content: [
            {
                id: 商品ID,
                name: 商品名称,
                image: {
                    id: 商品图片ID,
                    src: 商品图片链接
                },
                combination: 商品组合信息,
                specs: {
                    商品属性(如工艺): 属性值(如烫金),
                    ...
                },
                custom_specs: {
                    自定义属性(如尺寸): {
                        单位属性(如宽): 属性值(如1),
                        ...
                    },
                    ...
                },
                weight: 商品重量,
                count: 商品数量,
                price: 商品价格,
                remark: 商品备注
            },
            ...
        ],
        ^logs: 订单处理日志，操作用户为管理员且订单状态为已支付时返回 {
            administrator: 操作管理员昵称,
            action: 操作说明,
            created_at: 操作日期
        },
        goods_price: 商品总金额,
        goods_count: 商品总数量,
        total_weight: 商品总重量,
        freight: 运费,
        status: 订单状态 (已失效|待支付|已支付|待发货|已发货|已收货|已评论),
        discount_amount: 优惠券、活动等抵扣金额,
        member_discount: 会员折扣金额,
        total_price: 订单总金额,
        remark: 订单备注,
        created_at: 订单创建日期,
        ^expresses: 物流信息 (订单发货后显示) {
            id: 物流ID,
            company: 物流公司,
            track_no: 物流单号
        },
        ^balance_deducted: 账户余额抵扣 (订单支付后显示),
        ^pay_type: 支付方式 (订单支付后显示),
        ^paid_at: 订单支付日期 (订单支付后显示),
        ^audited_at: 订单审核日期 (订单审核通过后显示),
        ^delivered_at: 订单发货日期 (订单发货后显示),
        ^received_at: 订单收货日期 (订单收货后显示)
    }
    
### 40. 更新订单状态
    PUT /orders/{id}
    request:
    {
        status: 订单状态 (
            3 审核通过 (需订单管理权限),
            4 发货 (需订单管理权限),
            5 确认收货 (只能自己操作)
        )
    }
    
### 41. 获取首页分类信息
    GET /large_categories
    response:
    {
        {
            id: 分类ID,
            icon: {
                id: 分类图标ID,
                src: 分类图标链接
            },
            name: 分类名称,
            ^url: 点击跳转链接
            items: 主推条目 [
                {
                    id: 主键ID,
                    type: 主推条目类型 (
                        1 商品类型,
                        2 具体商品
                    ),
                    item: {
                        id: 主推条目ID,
                        name: 主推条目名称
                    },
                    is_hot: 是否热门,
                    is_new: 是否新品
                },
                ...
            ],
            categories: [
                {
                    id: 二级分类ID,
                    name: 二级分类名称,
                    ^url: 点击跳转链接,
                    items: 二级分类下条目 [
                        id: 主键ID,
                        type: 条目类型 (
                            1 商品类型,
                            2 具体商品
                        ),
                        item: {
                            id: 条目ID,
                            name: 条目名称
                        },
                        is_hot: 是否热门,
                        is_new: 是否新品
                    ]
                },
                ...
            ]
        },
        ...
    }
    
### 42. 获取优惠券列表
    GET /coupons
    response:
    {
        data:
        [
            {
                id: 优惠券ID,
                coupon_no: 优惠券编号,
                name: 优惠券名称,
                type: 优惠券类型 (满减|抵扣),
                quota: 优惠券金额,
                ^satisfy: 需满足金额，type为满减时返回,
                number: 优惠券数量,
                surplus: 剩余数量,
                is_received: 是否已领取过,
                finished_at: 过期时间,
                created_at: 创建时间
            },
            ...
        ]
    }
    
### 43. 领取优惠券
    POST /user_coupons
    request:
    {
        coupon_no: 优惠券编号
    }
    
### 44. 全部商品
    GET /large_categories/{id}
    response:
    {
        large_categories: 商品分类
        [
            {
                id: 分类ID,
                name: 分类名称
            },
            ...
        ],
        data:
        [
            {
                id: 商品ID,
                image: {
                    id: 商品图片ID,
                    src: 商品图片链接
                },
                name: 商品名称,
                summary: 商品描述,
                status: 商品状态(未上架|销售中),
                sales: 销量,
                price: 起价,
                comment_count: 评价数量
            },
            ...
        ]
    }
    
### 45. 查看商品详情
    GET /entities/{id}
    response:
    {
        id: 商品ID,
        type: {
            id: 商品类型ID,
            name: 商品类型名称
        },
        ^secondary_type: {
            id: 商品二级类型ID,
            name: 商品二级类型名称
        },
        images: 商品相册
        [
            {
                id: 图片ID,
                src: 图片链接
            },
            ...
        ],
        name: 商品名称,
        summary: 商品描述,
        body: 商品介绍,
        lead_time: 商品出货周期,
        custom_number: 是否支持用户自定义数量(
            0 不支持,
            1 支持单人数量,
            2 支持多人数量
        ),
        price: 起价,
        title: 页面标题,
        keywords: 页面关键词,
        describe: 页面描述,
        specs: 商品常规属性
        [
            {
                attribute: 属性名称（如颜色）,
                values: 属性值（如['红色', '黄色', '绿色']）
            },
            ...
        ],
        custom_specs: 允许用户自定义的属性
        [
            {
                attribute: 属性名称（如尺寸）,
                values:
                [
                    {
                        name: 单位属性（如宽）,
                        unit: 属性单位（如m）,
                        min: 支持的最小值（如0.5）,
                        max: 支持的最大值（如2）,
                        default: 默认值（如1）
                    },
                    ...
                ]
            },
            ...
        ],
        combinations: 商品组合
        [
            {
                id: 组合ID,
                combination: 组合（如 烫金|水晶|10盒，根据商品常规属性的顺序组合）,
                price: 该组合价格,
                weight: 该组合重量
            },
            ...
        ],
        disabled_combinations: 不可选的组合（如 ['烫金|炫金|40盒', '烫银|铂金|40盒']）,
        comments: 商品评价
        {
            data:
            [
                {
                    id: 评价ID,
                    ^user: 非匿名评价时返回 {
                        id: 评价用户ID,
                        nickname: 评价用户昵称,
                        avatar: 评价用户头像
                    },
                    goods: {
                        id: 评价商品ID,
                        name: 评价商品名称
                    },
                    target: 评价商品组合,
                    goods_comment: 商品评价,
                    service_comment: 服务评价,
                    images:
                    [
                        {
                            id: 评价图片id,
                            src: 评价图片链接
                        },
                        ...
                    ],
                    describe_grade: 描述相符分数,
                    seller_grade: 卖家服务分数,
                    logistics_grade: 物流服务分数,
                    is_anonymous: 是否匿名评价,匿名时不返回user字段,
                    created_at: 评价日期
                },
                ...
            ]
        },
        free_express: 包邮起步价,
        comment_count: 评价数量,
        status: 状态（未上架|销售中）,
        sales: 销量,
        created_at: 创建日期
    }
    
### 46. 验证验证码
    POST /code/validate
    request:
    {
        verification_code: 验证码，
        verification_token: 获取验证码接口返回的verification_token
    }
    response:
    {
        result: true
    }
    
### 47. 获取会员等级信息
    GET /member_levels
    response:
    [
        {
            id: 会员等级ID,
            icon: {
                id: 等级图标ID,
                src: 等级图标链接
            },
            name: 等级名称,
            accumulate_points: 升级所需积分,
            discount: 折扣率
        },
        ...
    ]
    
### 48. 首页Banner
    GET /banners
    response:
    [
        {
            id: BannerID,
            image: {
                id: 图片ID,
                src: 图片链接
            },
            url: 点击跳转链接
        },
        ...
    ]
    
### 49. 新品推荐
    GET /recommend_new_entities
    response:
    [
        {
            id: 新品推荐ID,
            image: {
                id: 图片ID,
                src: 图片链接
            },
            url: 点击跳转链接
        },
        ...
    ]
    
### 50. 推荐商品
    GET /recommend_entities
    response:
    {
        '推荐名片': [
            {
                id: 推荐ID,
                image: {
                    id: 图片ID,
                    src: 图片链接
                },
                url: 点击跳转链接
            },
            ...
        ],
        '企业办公': 同上,
        '营销宣传': 同上,
        '数码速印': 同上
    }
    
### 51. 合作伙伴
    GET /partners
    response:
    [
        {
            id: 合作伙伴ID,
            image: {
                id: 图片ID,
                src: 图片链接
            },
            url: 跳转链接
        },
        ...
    ]
    
### 52. 友情链接
    GET /links
    response:
    [
        {
            id: 友链ID,
            name: 友链名称,
            url: 跳转链接
        },
        ...
    ]
### 53. 推荐新闻
    GET /recommend_news
    response:
    {
        '印刷活动': [
            {
                id: 推荐新闻ID,
                title: 新闻标题,
                url: 点击跳转链接
            },
            ...
        ],
        '印刷知识': 同上
    }
    
### 54. 获取新闻分类
    GET /news_categories
    response:
    [
        {
            id: 分类ID,
            title: 分类名称,
            news: 新闻列表 {
                data:
                [
                    {
                        id: 新闻ID,
                        category: {
                            id: 所属分类ID,
                            title: 所属分类标题
                        },
                        image: {
                            id: 图片ID,
                            src: 图片链接
                        },
                        title: 新闻标题,
                        from: 来源,
                        summary: 摘要,
                        created_at: 发布日期,
                        updated_at: 更新日期
                    },
                    ...
                ]
            }
        },
        ...
    ]
    
### 55. 获取新闻列表
    GET /news_categories/{id}
    response:
    {
        id: 新闻分类ID,
        title: 新闻分类标题,
        news: {
            data:
            [
                {
                    id: 新闻ID,
                    category: {
                        id: 所属分类ID,
                        title: 所属分类标题
                    },
                    image: {
                        id: 图片ID,
                        src: 图片链接
                    },
                    title: 新闻标题,
                    from: 来源,
                    summary: 摘要,
                    created_at: 发布日期,
                    updated_at: 更新日期
                },
                ...
            ]
        }
    }
    
### 56. 查看新闻详情
    GET /news/{id}
    response:
    {
        id: 新闻ID,
        category: {
            id: 所属分类ID,
            title: 所属分类标题
        },
        image: {
            id: 图片ID,
            src: 图片链接
        },
        title: 新闻标题,
        from: 来源,
        summary: 摘要,
        body: 新闻内容,
        created_at: 发布日期,
        updated_at: 更新日期
    }
    
### 57. 新闻相关阅读及最新发布
    GET /news/recommend
    response:
    {
        relevance: 相关阅读
        [
            {
                id: 新闻ID,
                category: {
                    id: 所属分类ID,
                    title: 所属分类标题
                },
                image: {
                    id: 图片ID,
                    src: 图片链接
                },
                title: 新闻标题,
                from: 来源,
                summary: 摘要,
                created_at: 发布日期,
                updated_at: 更新日期
            },
            ...
        ],
        new: 最新发布
        [
            {
                id: 新闻ID,
                category: {
                    id: 所属分类ID,
                    title: 所属分类标题
                },
                image: {
                    id: 图片ID,
                    src: 图片链接
                },
                title: 新闻标题,
                from: 来源,
                summary: 摘要,
                created_at: 发布日期,
                updated_at: 更新日期
            },
            ...
        ]
    }
    
### 58. 其他印刷新闻
    GET /news/other
    request: {
        id: 当前正在浏览的新闻ID
    }
    response:
    [
        {
            id: 新闻ID,
            category: {
                id: 所属分类ID,
                title: 所属分类标题
            },
            image: {
                id: 图片ID,
                src: 图片链接
            },
            title: 新闻标题,
            from: 来源,
            summary: 摘要,
            created_at: 发布日期,
            updated_at: 更新日期
        },
        ...
    ]
    
### 59. 商品推荐
    GET /entities/recommend
    response:
    [
        {
            id: 商品ID,
            image: {
                id: 商品图片ID,
                src: 商品图片链接
            },
            name: 商品名称,
            summary: 商品描述,
            status: 商品状态(未上架|销售中),
            sales: 销量,
            price: 起价,
            comment_count: 评价数量
        },
        ...
    ]
    