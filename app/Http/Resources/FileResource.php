<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/18
 * Time: 16:07
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'src' => config('app.url') . '/storage/' . $this->src
        ];
    }
}