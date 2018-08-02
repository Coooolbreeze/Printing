<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/2
 * Time: 11:17
 */

namespace App\Http\Resources;


class MessageResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (MessageResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}