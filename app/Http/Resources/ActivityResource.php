<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 11:32
 */

namespace App\Http\Resources;


use App\Enum\ActivityStatusEnum;
use App\Models\Entity;
use App\Services\Tokens\TokenFactory;

class ActivityResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new ActivityResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'image' => new ImageResource($this->image),
            'name' => $this->name,
            'describe' => $this->describe,
            'entities' => $this->getEntities(),
            'status' => $this->convertStatus($this->status),
            'finished_at' => (string)$this->finished_at,
            'created_at' => (string)$this->created_at
        ]);
    }

    public function convertStatus($value)
    {
        $status = [
            ActivityStatusEnum::NOT_STARTED => '未开始',
            ActivityStatusEnum::ONGOING => '进行中',
            ActivityStatusEnum::FINISHED => '已结束'
        ];
        return $status[$value];
    }

    public function getEntities()
    {
        if (TokenFactory::isAdmin()) {
            return $this->entities()->pluck('id');
        } else {
            return new EntityCollection($this->entities()->paginate(Entity::getLimit()));
        }
    }
}