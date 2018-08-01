<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/1
 * Time: 11:30
 */

namespace App\Http\Resources;


class OrderLogResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'administrator' => $this->administrator,
            'action' => $this->action,
            'created_at' => (string)$this->created_at
        ]);
    }
}