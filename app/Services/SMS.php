<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/22
 * Time: 17:53
 */

namespace App\Services;


require_once dirname(__DIR__) . '/../vendor/aliyun-dysms/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use Illuminate\Support\Facades\Input;

// 加载区域结点配置
Config::load();

class SMS
{

    static $acsClient = null;

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient()
    {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = \config('sms.access_key_id'); // AccessKeyId

        $accessKeySecret = \config('sms.access_key_secret'); // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if (static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     *
     * @param $phoneNum
     * @param $code
     * @return mixed|\SimpleXMLElement
     */
    public static function sendSms($phoneNum, $code)
    {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        // $request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phoneNum);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName(\config('sms.sign_name'));

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode(\config('sms.template_code'));

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            "code" => $code
        ), JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        // $request->setOutId("yourOutId");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

    /**
     * 短信发送记录查询
     *
     * @param $phoneNum
     * @param $date
     * @return mixed|\SimpleXMLElement
     */
    public static function querySendDetails($phoneNum, $date = null)
    {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，短信接收号码
        $request->setPhoneNumber($phoneNum);

        // 必填，短信发送日期，格式Ymd，支持近30天记录查询
        $request->setSendDate($date ?: now()->format('Ymd'));

        // 必填，分页大小
        $request->setPageSize(request('limit') ?: 10);

        // 必填，当前页码
        $request->setCurrentPage(request('page') ?: 1);

        // 选填，短信发送流水号
        // $request->setBizId("yourBizId");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

}
