<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 14:22
 */

namespace App\Http\Resources;


class EntityResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (EntityResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}