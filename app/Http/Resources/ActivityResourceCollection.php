<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 11:33
 */

namespace App\Http\Resources;


class ActivityResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (ActivityResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}