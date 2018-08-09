# 易特印API文档

### 说明
* 参数前带 ^ 为非必填字段或非必返回字段

### 公共请求参数
    base_url: http://printing.besthtml5.com/api
    header:
    {
        token: 登录或注册接口返回的access_token
    }

### 公共返回参数
    {
        status: 请求状态 (success 或者 error),
        code: 状态码 (
            200请求成功，
            201资源创建成功，
            400请求失败，
            401未登录或登录已过期，
            403权限不足，
            404请求资源未找到，
            500服务器故障
        )，
        error_code: 错误码，当status为error时返回 (
            10003权限不足，
            20000账号不存在，
            20001密码错误，
            20002账号已被注册，
            20003注册失败，
            20005修改密码失败，
            20006验证码错误，
            20007账号格式错误，
            999其他错误，具体错误信息请查看message字段
        ),
        message: 提示信息，当status为error或者本次操作无数据返回时(如资源创建、更新、删除等操作),
        data: 请求到的数据，下列所有接口中的response如无特殊说明都是指这里面的数据
    }
    
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
        ^is_register: true (注册时需加上此字段)
    }
    response:
    {
        verification_token: 验证令牌
    }
    
### 4. 获取邮箱验证码
    POST /mail
    request:
    {
        email: 邮箱地址,
        ^is_register: true (注册时需加上此字段)
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
        ^is_admin: true (管理员登录需加上此字段)
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
    
### 10. 微信登录
    POST /wechat/login

### 11. 绑定手机号
    POST /bind/phone
    request:
    {
        verification_code: 验证码，
        verification_token: 获取短信验证码接口返回的verification_token
    }

### 12. 绑定邮箱
    POST /bind/email
    request:
    {
        verification_code: 验证码，
        verification_token: 获取邮箱验证码接口返回的verification_token
    }

### 13. 解绑登录方式
    POST /unbind
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
    
### 15. 获取拥有的权限列表
    POST /users/self/permissions
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