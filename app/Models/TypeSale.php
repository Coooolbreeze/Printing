<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/9
 * Time: 12:50
 */

namespace App\Models;


class TypeSale extends Model
{
    public static function write($type, $price)
    {
        $typeModel = self::where('type', $type)->first();

        if ($typeModel) {
            $typeModel->increment('sales_volume', $price);
        } else {
            self::create([
                'type' => $type,
                'sales_volume' => $price
            ]);
        }

        return true;
    }
}