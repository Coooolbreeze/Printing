<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/7
 * Time: 15:09
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * 需要隐藏的字段
     *
     * @var array
     */
    protected $withoutFields = [];

    /**
     * 需要显示的字段
     *
     * @var array
     */
    protected $showFields = [];

    public function hide(array $fields)
    {
        $this->withoutFields = $fields;
        return $this;
    }

    public function show(array $field)
    {
        $this->showFields = $field;
        return $this;
    }

    protected function filterFields($array)
    {
        if (!empty($this->showFields))
            return collect($array)->only($this->showFields)->toArray();

        return collect($array)->forget($this->withoutFields)->toArray();
    }
}