<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/14
 * Time: 16:07
 */

namespace App\Http\Resources;


use App\Models\News;

class NewsCategoryResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'title' => $this->title,
            'news' => new NewsCollection($this->news()->latest()->paginate(News::getLimit()))
        ]);
    }
}