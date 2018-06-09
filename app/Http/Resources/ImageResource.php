<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/7
 * Time: 16:46
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'src' => config('app.url') . '/storage/' . $this->src
        ];
    }
}