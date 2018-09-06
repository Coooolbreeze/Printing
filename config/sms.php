<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/22
 * Time: 21:02
 */

return [
    /**
     * 阿里云accessKeyId
     */
    'access_key_id' => env('ALI_ACCESS_KEY_ID'),

    /**
     * 阿里云accessKeySecret
     */
    'access_key_secret' => env('ALI_ACCESS_KEY_SECRET'),

    /**
     * 短信签名
     */
    'sign_name' => env('ALI_SIGN_NAME'),

    /**
     * 短信模板code
     */
    'template_code' => env('ALI_TEMPLATE_CODE'),

    /**
     * 通知订单状态模板
     */
    'order_status_template_code' => env('ALI_ORDER_STATUS_TEMPLATE_CODE')
];