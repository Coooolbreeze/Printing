<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/30
 * Time: 18:13
 */

namespace App\Http\Resources;


class ReceiptResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new ReceiptResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'user' => (new UserResource($this->user))->show(['id', 'nickname']),
            'order' => OrderResource::collection($this->orders)->show(['id', 'title']),
            'company' => $this->company,
            'tax_no' => $this->tax_no,
            'contact' => $this->contact,
            'contact_way' => $this->contact_way,
            'address' => $this->address,
            'money' => $this->money,
            'is_receipted' => (bool)$this->is_receipted,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at
        ]);
    }
}