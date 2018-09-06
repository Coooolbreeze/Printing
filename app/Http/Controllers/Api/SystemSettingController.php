<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/6
 * Time: 10:26
 */

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

class SystemSettingController extends ApiController
{
    public function index()
    {
        return $this->success([
            'sms_notify' => config('setting.sms_notify'),
            'payment_notify_email' => config('setting.payment_notify_email'),
            'custom_service_qq' => config('setting.custom_service_qq'),
            'custom_service_email' => config('setting.custom_service_email'),
            'custom_service_address' => config('setting.custom_service_address')
        ]);
    }

    public function customService()
    {
        return $this->success([
            'qq' => config('setting.custom_service_qq'),
            'email' => config('setting.custom_service_email'),
            'address' => config('setting.custom_service_address')
        ]);
    }

    public function store(Request $request)
    {
        isset($request->sms_notify) &&
        setEnv(['SMS_NOTIFY' => $request->sms_notify]);

        isset($request->payment_notify_email) &&
        setEnv([
            'PAYMENT_NOTIFY_EMAIL' => $request->payment_notify_email != 'false' ? $request->payment_notify_email : ''
        ]);

        isset($request->custom_service_qq) &&
        setEnv([
            'CUSTOM_SERVICE_QQ' => $request->custom_service_qq != 'false' ? $request->custom_service_qq : ''
        ]);

        isset($request->custom_service_email) &&
        setEnv([
            'CUSTOM_SERVICE_EMAIL' => $request->custom_service_email != 'false' ? $request->custom_service_email : ''
        ]);

        isset($request->custom_service_address) &&
        setEnv([
            'CUSTOM_SERVICE_ADDRESS' => $request->custom_service_address != 'false' ? $request->custom_service_address : ''
        ]);

        return $this->message('修改成功');
    }
}