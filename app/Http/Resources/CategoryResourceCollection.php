<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 17:53
 */

namespace App\Http\Resources;


class CategoryResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (CategoryResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}