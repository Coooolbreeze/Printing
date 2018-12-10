<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/1
 * Time: 9:39
 */

namespace App\Http\Resources;


use App\Services\KDN;
use Carbon\Carbon;

class OrderExpressResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'company' => $this->company,
            'code' => $this->code,
            'tracking_no' => $this->tracking_no,
            'logistics' => $this->queryLogistics()
        ]);
    }

    /**
     * TODO 查询物流信息
     *
     * @return array
     */
    public function queryLogistics()
    {
        $info = (new KDN($this->order->id))->Logistics($this);

        if (!$info['Success']) {
            return [
                [
                    'time' => (string)Carbon::now(),
                    'message' => '暂无物流信息'
                ]
            ];
        }

        $arr = [];
        foreach ($info['Traces'] as $value) {
            array_push($arr, [
                'time' => $value['AcceptTime'],
                'message' => $value['AcceptStation']
            ]);
        }
        return $arr;
    }
}