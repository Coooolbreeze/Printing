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
        error_code: 错误码，当status为error时返回 (
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
        message: 提示信息，当status为error或者本次操作无数据返回时(如资源创建、更新、删除等操作),
        data: 请求到的数据，下列所有response如无特殊说明都是指这里面的数据
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
        ^is_read: 0|1 指定返回已读或未读消息
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
                    detail: 详细地址,
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