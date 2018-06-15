<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/20
 * Time: 23:31
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;

/**
 * App\Models\Model
 *
 * @mixin \Eloquent
 */
class Model extends EloquentModel
{
    protected $guarded = [];

    public static function saveAll(array $data)
    {
        $self = new static();

        $columns = Schema::getColumnListing($self->getTable());

        foreach ($data as &$value) {
            if (in_array('created_at', $columns)) $value['created_at'] = Carbon::now();
            if (in_array('updated_at', $columns)) $value['updated_at'] = Carbon::now();
        }

        unset($value);

        return $self->insert($data);
    }

    public static function getLimit($defaultLimit = 15)
    {
        return Input::get('limit') ?: $defaultLimit;
    }

    public static function pagination($defaultLimit = 15)
    {
        return (new static())->paginate(self::getLimit($defaultLimit));
    }
}