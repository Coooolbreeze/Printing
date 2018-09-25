<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/25
 * Time: 21:57
 */

namespace App\Http\Controllers\Api;


class MapController extends ApiController
{
    public function index()
    {
        $filed = "id,title,CONCAT(if(MONTH(created_at)>9,MONTH(created_at),CONCAT(0,MONTH(created_at))),'-',if(DAY(created_at)>9,DAY(created_at),CONCAT(0,DAY(created_at)))) as created_at";

        $res = \DB::select("SELECT 1 as type,$filed FROM news UNION ALL SELECT 2 as type,$filed FROM helps;");

        return $this->success($res);
    }
}