<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/5
 * Time: 15:04
 */

namespace App\Http\Controllers\Api;


use Spatie\Permission\Models\Permission;

class PermissionController extends ApiController
{
    public function index()
    {
        return $this->success(Permission::all(['id', 'name']));
    }
}