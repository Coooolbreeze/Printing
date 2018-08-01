<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/30
 * Time: 17:35
 */

namespace App\Models;


use App\Exceptions\BaseException;
use App\Services\Tokens\TokenFactory;

/**
 * App\Models\Receipt
 *
 * @property string $address
 * @property string $company
 * @property string $contact
 * @property string $contact_way
 * @property \Carbon\Carbon|null $created_at
 * @property int $id
 * @property string $tax_no
 * @property \Carbon\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereContactWay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereTaxNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereUserId($value)
 * @mixin \Eloquent
 * @property int $is_receipted
 * @property float $money
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereIsReceipted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Receipt whereMoney($value)
 */
class Receipt extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    /**
     * @param $receipt
     * @param $money
     * @return Receipt|\Illuminate\Database\Eloquent\Model
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public static function receipted($receipt, $money)
    {
        $receiptedMoney = config('setting.receipted_money');

        if ($money < $receiptedMoney)
            throw new BaseException('发票金额不能小于' . $receiptedMoney . '元');

        return self::create([
            'user_id' => TokenFactory::getCurrentUID(),
            'company' => $receipt['company'],
            'tax_no' => $receipt['tax_no'],
            'contact' => $receipt['contact'],
            'contact_way' => $receipt['contact_way'],
            'address' => $receipt['address'],
            'money' => $money
        ]);
    }
}