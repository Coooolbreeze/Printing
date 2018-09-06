<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/6
 * Time: 15:52
 */

namespace App\Http\Resources;


class LogResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'url' => $this->url,
            'ip' => $this->ip,
            'action' => $this->action,
            'created_at' => (string)$this->created_at
        ];
    }
}