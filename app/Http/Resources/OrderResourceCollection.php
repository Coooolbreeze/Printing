<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/27
 * Time: 17:34
 */

namespace App\Http\Resources;


class OrderResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (OrderResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}