<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 15:51
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\TypeCollection;
use App\Http\Resources\TypeResource;
use App\Models\Type;

class TypeController extends ApiController
{
    public function index()
    {
        return $this->success(new TypeCollection(Type::pagination()));
    }

    public function show(Type $type)
    {
        return $this->success(new TypeResource($type));
    }
}