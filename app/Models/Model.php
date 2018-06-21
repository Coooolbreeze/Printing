<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/3/20
 * Time: 23:31
 */

namespace App\Models;


use App\Exceptions\BaseException;
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

    //批量更新
    public static function updateBatch($multipleData = [])
    {
        try {
            if (empty($multipleData)) {
                throw new \Exception("数据不能为空");
            }
            $tableName = \DB::getTablePrefix() . (new static())->getTable(); // 表名
            $firstRow = current($multipleData);

            $updateColumn = array_keys($firstRow);
            // 默认以id为条件更新，如果没有ID则以第一个字段为条件
            $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
            unset($updateColumn[0]);
            // 拼接sql语句
            $updateSql = "UPDATE " . $tableName . " SET ";
            $sets = [];
            $bindings = [];
            foreach ($updateColumn as $uColumn) {
                $setSql = "`" . $uColumn . "` = CASE ";
                foreach ($multipleData as $data) {
                    $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$uColumn];
                }
                $setSql .= "ELSE `" . $uColumn . "` END ";
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn = collect($multipleData)->pluck($referenceColumn)->values()->all();
            $bindings = array_merge($bindings, $whereIn);
            $whereIn = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
            // 传入预处理sql语句和对应绑定数据
            return \DB::update($updateSql, $bindings);
        } catch (\Exception $e) {
            throw new BaseException('更新失败');
        }
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