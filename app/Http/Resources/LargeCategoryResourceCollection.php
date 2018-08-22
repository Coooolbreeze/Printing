<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/22
 * Time: 15:00
 */

namespace App\Http\Resources;


class LargeCategoryResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (LargeCategoryResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}