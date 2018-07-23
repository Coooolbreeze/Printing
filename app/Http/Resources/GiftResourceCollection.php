<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/23
 * Time: 16:25
 */

namespace App\Http\Resources;


class GiftResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (GiftResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}