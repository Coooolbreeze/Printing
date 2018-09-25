<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/7
 * Time: 12:46
 */

namespace App\Http\Resources;


class FinanceStatisticResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_no' => $this->order_no,
            'price' => ($this->type == 1 ? '' : '-') . $this->price,
            'type' => $this->type == 1 ? '收入' : '退款',
            'created_at' => (string)$this->created_at
        ];
    }
}