<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UserAuth
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAuth onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAuth withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserAuth withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $platform 登录平台
 * @property string $identity_type 登录类型
 * @property string $identifier 登录标识
 * @property string $credential 登录凭证
 * @property string|null $remark 存储一些特定信息，如微信unionid
 * @property int $verified 0未验证 1已验证
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereCredential($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereIdentityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAuth whereVerified($value)
 */
class UserAuth extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
