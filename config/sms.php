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
     * 订单审核通过模板
     */
    'order_audited_template_code' => env('ORDER_AUDITED_TEMPLATE_CODE'),

    /**
     * 订单发货通知模板
     */
    'order_delivered_template_code' => env('ORDER_DELIVERED_TEMPLATE_CODE')
];