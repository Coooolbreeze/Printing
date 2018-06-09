<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/13
 * Time: 21:13
 */

namespace App\Http\Resources;


class UserResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (UserResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}