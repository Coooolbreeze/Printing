<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/1
 * Time: 9:39
 */

namespace App\Http\Resources;


class OrderExpressResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'company' => $this->company,
            'tracking_no' => $this->tracking_no
        ]);
    }
}