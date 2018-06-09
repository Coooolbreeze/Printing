<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/7
 * Time: 15:22
 */

namespace App\Http\Resources;


class NewsResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (NewsResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}