<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/13
 * Time: 1:02
 */

namespace App\Services;


use App\Exceptions\VerificationCodeException;
use Cache;

class VerificationCode
{
    /**
     * 生成验证码并保存
     *
     * @param $contact
     * @param int $length
     * @param int $minutes
     * @return array
     */
    public static function create($contact, $length = 6, $minutes = 5)
    {
        $code = getRandChar($length, true);

        $verificationToken = md5(getRandChar(32) . $_SERVER['REQUEST_TIME_FLOAT']);

        Cache::add($verificationToken, json_encode([
            'contact' => $contact,
            'code' => $code
        ]), $minutes);

        return [
            'verification_code' => $code,
            'verification_token' => $verificationToken
        ];
    }

    /**
     * 验证code
     *
     * @param $code
     * @param $verificationToken
     * @return mixed
     * @throws VerificationCodeException
     */
    public static function validate($code, $verificationToken)
    {
        $cacheValues = json_decode(Cache::get($verificationToken), true);

        if (!$cacheValues) throw new VerificationCodeException('验证码已过期');
        if ($code != $cacheValues['code']) throw new VerificationCodeException();

        return $cacheValues;
    }

    /**
     * 验证并返回联系方式
     *
     * @param $code
     * @param $verificationToken
     * @return mixed
     * @throws VerificationCodeException
     */
    public static function getContact($code, $verificationToken)
    {
        $cacheValues = self::validate($code, $verificationToken);

        Cache::forget($verificationToken);

        return $cacheValues['contact'];
    }
}