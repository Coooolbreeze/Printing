<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/25
 * Time: 11:24
 */

namespace App\Http\Resources;


class GiftOrderResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (GiftOrderResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}