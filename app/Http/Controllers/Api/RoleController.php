<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/7
 * Time: 12:54
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Role;

class RoleController extends ApiController
{
    public function index()
    {
        return $this->success(RoleResource::collection(Role::where('id', '>', 1)->get()));
    }
}