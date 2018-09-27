<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/24
 * Time: 12:20
 */

namespace App\Models;

use App\Exceptions\BaseException;
use App\Services\Tokens\TokenFactory;


/**
 * App\Models\Address
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $phone
 * @property string $province
 * @property string $city
 * @property string $county
 * @property string $detail
 * @property int $is_default
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereUserId($value)
 * @mixin \Eloquent
 */
class Address extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @param $id
     * @throws \App\Exceptions\BaseException
     * @throws \App\Exceptions\TokenException
     */
    public static function setDefault($id)
    {
        $addresses = TokenFactory::getCurrentUser()->addresses;
        $addresses->each(function ($item) use ($id) {
            $item['is_default'] = (int)($item->id === $id);
        });
        self::updateBatch($addresses->toArray());
    }

    /**
     * @param null $id
     * @return false|string
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public static function addressSnap($id = null)
    {
        if (!$id) {
            $address = TokenFactory::getCurrentUser()
                ->addresses()
                ->where('is_default', 1)
                ->first();
        } else {
            $address = self::find($id);
        }

        if (!$address) throw new BaseException('请选择地址或设置默认地址');

        return json_encode([
            'name' => $address->name,
            'phone' => $address->phone,
            'province' => $address->province,
            'city' => $address->city,
            'county' => $address->county,
            'detail' => $address->detail
        ]);
    }
}