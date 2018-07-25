<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/25
 * Time: 17:37
 */

namespace App\Http\Resources;


class BalanceRecordResourceCollection extends Collection
{
    protected function processCollection($request)
    {
        return $this->collection->map(function (BalanceRecordResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}