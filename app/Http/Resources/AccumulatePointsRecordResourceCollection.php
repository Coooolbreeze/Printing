<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/24
 * Time: 15:13
 */

namespace App\Http\Resources;


class AccumulatePointsRecordResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (AccumulatePointsRecordResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}