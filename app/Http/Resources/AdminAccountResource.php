<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/7
 * Time: 10:17
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;

class AdminAccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account' => $this->account,
            'roles' => $this->getRolesId($this->getRoleNames()),
            'permissions' => $this->getAllPermissions()->pluck('name')
        ];
    }

    private function getRolesId($roleNames)
    {
        return Role::whereIn('name', $roleNames)
            ->pluck('id');
    }
}