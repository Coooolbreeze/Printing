<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/30
 * Time: 18:14
 */

namespace App\Http\Resources;


class ReceiptResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (ReceiptResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}