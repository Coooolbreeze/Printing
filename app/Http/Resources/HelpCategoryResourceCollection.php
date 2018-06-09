<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 17:03
 */

namespace App\Http\Resources;


class HelpCategoryResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (HelpCategoryResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}