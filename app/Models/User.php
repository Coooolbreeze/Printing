<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Model
{
    use HasRoles, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [];
}