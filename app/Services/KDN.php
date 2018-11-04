<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/11/4
 * Time: 18:29
 */

namespace App\Services;


class KDN
{
    // 商户ID
    private $businessID;
    // 商户密钥
    private $appKey;
    // 请求地址
    private $reqUrl;

    public function __construct()
    {
        $this->businessID = config('kdn.business_id');
        $this->appKey = config('kdn.app_key');
        $this->reqUrl = config('kdn.req_url');
    }

    public function generate()
    {
        $eorder = [];
        $eorder["ShipperCode"] = "SF";
        $eorder["OrderCode"] = "012657700387";
        $eorder["PayType"] = 1;
        $eorder["ExpType"] = 1;

        $sender = [];
        $sender["Name"] = "李先生";
        $sender["Mobile"] = "18888888888";
        $sender["ProvinceName"] = "李先生";
        $sender["CityName"] = "深圳市";
        $sender["ExpAreaName"] = "福田区";
        $sender["Address"] = "赛格广场5401AB";

        $receiver = [];
        $receiver["Name"] = "李先生";
        $receiver["Mobile"] = "18888888888";
        $receiver["ProvinceName"] = "李先生";
        $receiver["CityName"] = "深圳市";
        $receiver["ExpAreaName"] = "福田区";
        $receiver["Address"] = "赛格广场5401AB";

        $commodityOne = [];
        $commodityOne["GoodsName"] = "其他";
        $commodity = [];
        $commodity[] = $commodityOne;

        $eorder["Sender"] = $sender;
        $eorder["Receiver"] = $receiver;
        $eorder["Commodity"] = $commodity;

        $jsonParam = json_encode($eorder, JSON_UNESCAPED_UNICODE);

        return $this->submitEOrder($jsonParam);
    }

    private function submitEOrder($requestData)
    {
        $datas = array(
            'EBusinessID' => $this->businessID,
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->appKey);
        $result = $this->sendPost($this->reqUrl, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    private function sendPost($url, $datas)
    {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if (empty($url_info['port'])) {
            $url_info['port'] = 80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        $httpheader .= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    private function encrypt($data, $appkey)
    {
        return urlencode(base64_encode(md5($data . $appkey)));
    }

    private function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }

            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }

    private function JSON($array)
    {
        $this->arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }
}