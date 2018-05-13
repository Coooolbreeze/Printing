<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/5/13
 * Time: 21:13
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class UserResourceCollection extends ResourceCollection
{
    protected $withoutFields = [];
    protected $showFields = [];

    public function toArray($request)
    {
        return $this->processCollection($request);
    }

    public function hide(array $fields)
    {
        $this->withoutFields = $fields;
        return $this;
    }

    public function show(array $fields)
    {
        $this->showFields = $fields;
        return $this;
    }

    protected function processCollection($request)
    {
        return $this->collection->map(function (UserResource $resource) use ($request) {
            if (!empty($this->showFields))
                return $resource->show($this->showFields)->toArray($resource);

            return $resource->hide($this->withoutFields)->toArray($resource);
        })->all();
    }
}