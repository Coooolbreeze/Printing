<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User permission($permissions)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User role($roles)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property string $nickname
 * @property string $avatar
 * @property int $sex
 * @property string|null $account
 * @property string|null $phone
 * @property string|null $email
 * @property int $is_bind_account
 * @property int $is_bind_phone
 * @property int $is_bind_email
 * @property int $is_bind_wx
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsBindAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsBindEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsBindPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsBindWx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @property int $is_admin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsAdmin($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Coupon[] $coupons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cart[] $carts
 * @property int $accumulate_points
 * @property int $history_accumulate_points
 * @property int $member_level_id
 * @property-read \App\Models\MemberLevel $memberLevel
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAccumulatePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereHistoryAccumulatePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereMemberLevelId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AccumulatePointsRecord[] $accumulatePointsRecords
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Address[] $addresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GiftOrder[] $giftOrders
 * @property float $balance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBalance($value)
 */
class User extends Model
{
    use HasRoles, SoftDeletes;

    protected $dates = ['deleted_at'];

    public function coupons()
    {
        return $this->belongsToMany('App\Models\Coupon');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    public function memberLevel()
    {
        return $this->belongsTo('App\Models\MemberLevel');
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function giftOrders()
    {
        return $this->hasMany('App\Models\GiftOrder');
    }

    public function accumulatePointsRecords()
    {
        return $this->hasMany('App\Models\AccumulatePointsRecord');
    }
}