<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/23
 * Time: 10:56
 */

namespace App\Http\Resources;


class SceneCategoryResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (SceneCategoryResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}