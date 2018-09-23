# 安装文档

### 下载源码
    git clone https://code.aliyun.com/yyml-pro/yty.git
### 进入根目录
    cd yty
### 安装依赖
    composer install --no-dev
### 目录权限配置
    chmod 777 -R storage
### 上传文件目录映射
    php artisan storage:link
### 生成应用密钥
    php artisan key:generate
### 修改配置文件
    cp .env.example .env
    vim .env
##### 主要配置项说明
    APP_NAME=Laravel            // 应用名称
    APP_ENV=local               // 当前环境 local|production
    APP_KEY=                    // 应用密钥 php artisan key:generate自动生成
    APP_DEBUG=true              // 是否开启debug
    APP_URL=http://localhost    // 网站域名
    
    AUTO_RECOMMEND=true         // 是否开启自动推荐
    
    DEFAULT_AVATAR_URL=         // 新注册用户默认头像链接
    
    ACCUMULATE_POINTS_MONEY=100 // 每积分需消费多少金额
    
    RECEIPTED_MONEY=200         // 开票最小金额

    ORDER_EXPIRE_IN=30          // 订单失效时间(分)
    
    FREE_EXPRESS=68             // 包邮价
    FIRST_WEIGHT=500            // 首重重量(g)
    ADDITIONAL_WEIGHT=500       // 续重重量(g)
    
    SMS_NOTIFY=false            // 是否开启短信通知用户订单状态
    
    PAYMENT_NOTIFY_EMAIL=       // 订单支付通知邮箱
    
    CUSTOM_SERVICE_QQ=          // 客服QQ
    CUSTOM_SERVICE_EMAIL=       // 客服邮箱
    CUSTOM_SERVICE_ADDRESS=     // 办公地址
    
    DB_CONNECTION=mysql         // 数据库类型
    DB_HOST=127.0.0.1           // 数据库地址
    DB_PORT=3306                // 数据库端口
    DB_DATABASE=homestead       // 数据库名称
    DB_USERNAME=homestead       // 用户名
    DB_PASSWORD=secret          // 密码
    
    CACHE_DRIVER=redis          // 使用redis作为缓存驱动
    QUEUE_DRIVER=redis          // 使用redis作为队列驱动
    
    REDIS_HOST=127.0.0.1        // redis地址
    REDIS_PASSWORD=null         // 密码
    REDIS_PORT=6379             // 端口
    
    ALI_ACCESS_KEY_ID=          // 阿里云access_key
    ALI_ACCESS_KEY_SECRET=      // 阿里云access_key_secret
    ALI_SIGN_NAME=              // 阿里云短信平台签名
    ALI_TEMPLATE_CODE=          // 验证码模板CODE 
    ORDER_AUDITED_TEMPLATE_CODE=    // 订单审核通过模板
    ORDER_DELIVERED_TEMPLATE_CODE=  // 订单发货通知模板
    
    MAIL_DRIVER=smtp            // 邮件服务器驱动
    MAIL_HOST=smtp.mailtrap.io  // 邮件服务器地址
    MAIL_PORT=2525              // 端口
    MAIL_USERNAME=null          // 用户名
    MAIL_PASSWORD=null          // 密码
    MAIL_ENCRYPTION=null        // 加密方式
    MAIL_FROM_ADDRESS=          // 发件人邮箱地址
    MAIL_FROM_NAME=             // 发件人姓名
    
    ACCESS_TOKEN_EXPIRE_IN=86400    // 访问令牌有效期（秒）
    REFRESH_TOKEN_EXPIRE_IN=604800  // 刷新令牌有效期（秒）
    
    WX_OPEN_APP_ID=         // 微信开放平台APPID
    WX_OPEN_APP_SECRET=     // 微信开放平台APPSECRET
    
    WECHAT_APP_ID=          // 微信支付APPID
    WECHAT_MCH_ID=          // 微信支付商户号
    WECHAT_KEY=             // 微信支付商户密钥
    WECHAT_CERT_CLIENT=     // 微信支付客户端证书路径(绝对路径，注意权限问题)
    WECHAT_CERT_KEY=        // 微信支付证书密钥路径(绝对路径，注意权限问题)
    
    ALI_APP_ID=             // 支付宝支付APPID
    ALI_PUBLIC_KEY=         // 支付宝支付公钥
    ALI_PRIVATE_KEY=        // 支付宝支付私钥
    
### 运行数据库迁移
    php artisan migrate --seed
### 安装Supervisor
    yum install supervisor
### 启动Supervisor
    /usr/bin/supervisord -c /etc/supervisord.conf
### 添加应用队列监控配置
    cd /etc/supervisord.d/
    vim laravel-worker.ini
    
    [program:laravel-worker]
    process_name=%(program_name)s_%(process_num)02d
    command= php 网站根目录 artisan queue:work --sleep=3 --tries=3 --daemon
    autostart=true
    autorestart=true
    user=root
    numprocs=4
    redirect_stderr=true
    stdout_logfile=//日志文件路径
### 重新加载配置文件
    supervisorctl reread
    supervisorctl update
### 启动队列进程
    supervisorctl start laravel-worker:*
    
---
---

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
    POST /sms
    request:
    {
        phone: 手机号,
        ^is_register: true (注册或绑定时需加上此字段)
    }
    response:
    {
        verification_token: 验证令牌
    }
    
### 4. 获取邮箱验证码
    POST /email
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
    
### 8. 微信登录
    POST /login
    request:
    {
        code: 用户授权重定向后微信授予的code参数,
        type: 'open'
    }
    response:
    {
        access_token: 令牌,
        refresh_token: 刷新令牌,可在令牌过期后进行刷新,过期时间为access_token_expire * 10
        access_token_expire:令牌有效期 单位秒
    }
    
### 9. 刷新令牌
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
    
### 10. 修改密码
    PUT /repassword
    request:
    {
        verification_code: 验证码，
        verification_token: 获取验证码接口返回的verification_token,
        password: 密码
    }

### 11. 绑定手机或邮箱
    POST /login_modes
    request:
    {
        verification_code: 短信或邮箱验证码，
        verification_token: 短信或邮箱验证码接口返回的verification_token
    }
    
### 12. 绑定微信登录
    POST /login_modes
    request:
    {
        code: 用户授权重定向后微信授予的code参数
    }
    
### 13. 解绑登录方式
    DELETE /login_modes
    request:
    {
        type: 需解绑的登录方式(phone|email|wx)
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
    
### 16. 获取我的站内信列表
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
    
### 17. 获取我的未读消息数
    GET /users/self/messages/unread_count
    response:
    {
        count: 未读消息数量
    }
    
### 18. 获取我的评价列表
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
    
### 19. 获取我的优惠券列表
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
    
### 20. 获取我的积分记录
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
    
### 21. 获取我的资产记录
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
    
### 22. 获取我的收货地址
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
    
### 23. 获取我的余额充值订单
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
    
### 24. 获取我的礼品订单
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
    
### 25. 获取我的商品订单
    GET /users/self/orders
    request:
    {
        ^unreceipt: true (筛选未开票订单)
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
                receipt: ,
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
    
### 26. 获取我的购物车商品信息
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
    
### 27. 获取我的发票信息
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
    
### 28. 查看用户信息
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
    
### 29. 添加商品到购物车
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
        ^remark: 备注 (多人数量信息，如 张三:10盒;李四:20盒;)
    }
    
### 30. 批量添加商品到购物车,用于用户登录后上传本地缓存中的购物车数据
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
    
### 31. 删除购物车商品
    DELETE /carts/{id}
    
### 32. 批量删除购物车商品
    DELETE /batch/carts
    request:
    {
        ids: 购物车商品ID数组，如[1,2,3]
    }
    
### 33. 创建商品订单
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
        ^coupon_no: 优惠券编号,
        ^address_id: 收货地址ID,不传时使用用户默认地址 (如果有),
        express_id: 快递公司ID,
        remark: 订单备注
    }
    response:
    {
        id: 订单ID
    }

### 34. 查看商品订单详情
    GET /orders/{id}
    response:
    {
        id: 订单ID,
        receipt: 同我的发票列表,
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
        ^expresses: 物流信息 (订单发货后显示) [
            {
                id: 物流ID,
                company: 物流公司,
                track_no: 物流单号，
                logistics: [
                    {
                        time: 更新时间,
                        message: 物流跟踪
                    }，
                    ...
                ]
            },
            ...
        ],
        ^balance_deducted: 账户余额抵扣 (订单支付后显示),
        ^pay_type: 支付方式 (订单支付后显示),
        ^paid_at: 订单支付日期 (订单支付后显示),
        ^audited_at: 订单审核日期 (订单审核通过后显示),
        ^delivered_at: 订单发货日期 (订单发货后显示),
        ^received_at: 订单收货日期 (订单收货后显示)
    }
    
### 35. 确认收货
    PUT /orders/{id}
    request:
    {
        status: 5
    }
    
### 36. 获取首页分类信息
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
    
### 37. 获取优惠券列表
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
    
### 38. 领取优惠券
    POST /user_coupons
    request:
    {
        coupon_no: 优惠券编号
    }
    
### 39. 全部商品
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
    
### 40. 查看商品详情
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
    
### 41. 验证验证码
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
    
### 42. 获取会员等级信息
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
    
### 43. 首页Banner
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
    
### 44. 新品推荐
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
    
### 45. 推荐商品
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
    
### 46. 合作伙伴
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
    
### 47. 友情链接
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
### 48. 推荐新闻
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
    
### 49. 获取新闻分类
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
    
### 50. 获取新闻列表
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
    
### 51. 查看新闻详情
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
    
### 52. 新闻相关阅读及最新发布
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
    
### 53. 其他印刷新闻
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
    
### 54. 商品推荐
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
    
### 55. 客服信息
    GET /custom_service
    response:
    {
        qq: QQ号,
        email: 邮箱,
        address: 办公地址
    }
    
### 56. 热门搜索关键词
    GET /hot_keywords
    response:
    [
        {
            id: ID,
            name: 关键词,
            url: 点击跳转链接
        },
        ...
    ]
    
### 57. 搜索商品
    GET /entities
    request:
    {
        keyword: 关键词,
        sort: 排序 (
            1 销量排序
        )
    }
    response:
    {
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
    
### 58. 添加收货地址
    POST /addresses
    request:
    {
        name: 收货人姓名,
        phone: 收货人手机号,
        province: 省,
        city: 市,
        county: 区,
        detail: 详细地址,
        is_default: 是否设为默认地址(0否|1是)
    }
    
### 59. 查看收获地址详情
    GET /addresses/{id}
    response:
    {
        name: 收货人姓名,
        phone: 收货人手机号,
        province: 省,
        city: 市,
        county: 区,
        detail: 详细地址,
        is_default: 是否是默认地址
    }
    
### 60. 更新收货地址
    PUT /addresses/{id}
    request:
    {
        name: 收货人姓名,
        phone: 收货人手机号,
        province: 省,
        city: 市,
        county: 区,
        detail: 详细地址,
        is_default: 是否是默认地址
    }
    
### 61. 删除收货地址
    DELETE /addresses/{id}
    
### 62. 获取配送公司
    GET /expresses
    request:
    {
        ^province: 省份 (获取支持该省份的配送公司)
    }
    response:
    [
        {
            id: 配送ID,
            name: 配送公司名称,
            first_unity: 首重重量(g),
            additional_unity: 续重重量(g),
            first_weight: 首重价格,
            additional_weight: 续重价格,
            capped: 封顶价,
            regions: 支持的配送区域(省)
        },
        ...
    ]
    运费计算公式：Math.ceil((订单商品总重量 - 首重重量) / 续重重量) * 续重价格 + 首重价格。超过封顶价则使用封顶价
    
### 63. 支付宝支付订单 (将URL和请求参数拼接好后，直接使用window.open打开)
    GET /alipay/pay
    request:
    {
        token: 访问令牌,
        order_id: 订单ID,
        ^balance: 使用余额抵扣金额
    }
    支付宝支付目前是沙箱环境，需使用沙箱钱包支付
    下载地址：https://sandbox.alipaydev.com/user/downloadApp.htm
    登录账号：qufmah5452@sandbox.com
    登录密码：111111
    支付密码：111111
    具体说明查看：https://openhome.alipay.com/platform/appDaily.htm?tab=info
    
### 64. 微信支付订单
    POST /wxpay/pay
    request:
    {
        order_id: 订单ID,
        balance: 使用余额抵扣金额
    }
    response:
    {
        code_url: 支付二维码参数 (使用该参数生成二维码让用户扫码支付)
    }
    当用户打开扫描二维码页面时，轮询获取订单详情接口，当订单状态为已支付时，自动跳转到支付成功页面
    
### 65. 使用账户余额支付订单
    POST /balance/pay
    request:
    {
        order_id: 订单ID
    }
    
### 65. 支付宝充值余额 (将URL和请求参数拼接好后，直接使用window.open打开)
    GET /alipay/recharge
    request:
    {
        token: 访问令牌,
        price: 充值金额(元)
    }
    
### 66. 微信充值余额
    POST /wxpay/recharge
    request:
    {
        price: 充值金额(元)
    }
    response:
    {
        id: 该充值订单ID,
        code_url: 支付二维码参数 (使用该参数生成二维码让用户扫码支付)
    }
    当用户打开扫描二维码页面时，轮询获取充值订单详情接口，当is_paid为true时，自动跳转到支付成功页面
    
### 67. 获取充值订单详情
    GET  /recharge_orders/{id}
    response:
    {
        is_paid: 是否已支付
    }
    
### 68. 最新活动列表
    GET /activities
    response:
    {
        data:
        [
            {
                id: 活动ID,
                image: {
                    id: 图片ID,
                    src: 图片链接
                },
                name: 活动名称,
                describe: 活动描述,
                status: 活动状态 (进行中|已结束),
                finished_at: 结束时间,
                created_at: 创建时间
            },
            ...
        ]
    }
    
### 69. 查看活动详情
    GET /activities/{id}
    response:
    {
        同上，增加entities参数
        entities: {
            data: 活动商品列表
            [
                {
                    id: 商品ID,
                    type: 商品类型,
                    image: 商品图片,
                    name: 商品名称,
                    summary: 商品描述,
                    price: 商品价格,
                    comment_count: 商品评价数,
                    sales: 商品销量
                },
                ...
            ]
        }
    }
    
### 70. 场景列表
    GET /scenes
    response:
    [
        {
            id: 场景ID,
            name: 场景名称,
            describe: 场景描述
        },
        ...
    ]
    
### 71. 场景详情
    GET /scenes/{id}
    response:
    {
        同上，增加categories参数
        categories: 场景下分类及商品
        [
            {
                id: 场景分类ID,
                name: 场景分类名称,
                goods: 商品列表
                [
                    {
                        image: {
                            id: 图片ID,
                            src: 图片链接
                        },
                        name: 商品名称,
                        describe: 商品描述,
                        url: 商品链接
                    },
                    ...
                ]
            },
            ...
        ]
    }
    
### 72. 申请发票
    POST /receipts
    request:
    {
        order_ids: 需要申请发票的订单ID数组，如[1,2,3],
        receipt_info: {
            company: 公司名称,
            tax_no: 纳税号,
            contact: 联系人,
            contact_way: 联系方式,
            address: 地址
        }
    }
    
    
### 73. 评价订单
    POST /comments
    request:
    {
        order_id: 订单ID,
        comments: 订单内所有商品的评价
        [
            {
                commentable_id: 商品ID,
                target: 商品组合,
                goods_comment: 商品评价,
                service_comment: 服务评价,
                describe_grade: 描述相符分数 (0.5-5),
                seller_grade: 卖家服务分数 (0.5-5),
                logistics_grade: 物流服务分数 (0.5-5),
                is_anonymous: 是否匿名,
                images: 评价图片数组，如[1,2,3]
            },
            ...
        ]
    }
    
### 74. 积分礼品列表
    GET /gifts
    response:
    {
        data:
        [
            id: 商品ID,
            name: 商品名称,
            image: {
                id: 图片ID,
                src: 图片链接
            },
            accumulate_points: 兑换所需积分,
            stock: 剩余库存
        ]
    }
    
### 75. 兑换礼品
    POST /gift_orders
    request:
    {
        gift_id: 礼品ID,
        number: 数量,
        ^address_id: 收货地址ID (不传使用用户默认地址，如果有)
    }
    
### 76. 帮助中心文章列表
    GET /helps
    response:
    {
        data:
        [
            {
                id: 文章ID,
                category: {
                    id: 所属分类ID,
                    name: 所属分类名称
                },
                title: 文章标题,
                body: 文章内容,
                updated_at: 最近修改时间
            },
            ...
        ]
    }
    
### 77. 帮助中心文章详情
    GET /helps/{id}
    response:
    {
        同上
    }
    

### 78. 获取我的关注列表
    GET /users/self/follows
    response: 同商品列表
    
### 79. 关注商品
    POST /follows
    request:
    {
        entity_id: 商品ID，多个ID用数组
    }
    
### 80. 取消关注商品
    DELETE /follows
    request:
    {
        entity_id: 商品ID，多个ID用数组
    }
    
### 81. 取消订单
    PUT /orders/{id}
    request:
    {
        status: 0
    }
    
### 82. 获取我的未开票订单列表
    见25
    
### 83. 帮助中心分类列表
    GET /help_categories
    response:
    [
        {
            id: 分类ID,
            name: 分类名称,
            helps: 分类下文章列表
            [
                {
                    id: 文章ID,
                    category: {
                        id: 所属分类ID,
                        name: 所属分类名称
                    },
                    title: 文章标题,
                    body: 文章内容,
                    updated_at: 最近修改时间
                },
                ...
            ]
        },
        ...
    ]
    
### 84. 获取分类下文章列表
    GET /help_categories/{id}
    response:
    {
        同上
    }
    
### 85. 获取最新交易订单 (返回最新的10条订单，前端定时轮询该接口)
    GET /new_orders
    response:
    [
        {
            id: 订单ID,
            order_no: 订单编号,
            title: 订单标题,
            user: {
                id: 购买用户ID,
                nickname: 购买用户昵称,
                phone: 购买用户手机号 (如果已绑定手机)
            },
            created_at: 订单创建时间
        },
        ...
    ]
    