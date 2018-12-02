<?php

namespace App\Imports;

use App\Exceptions\BaseException;
use App\Models\Combination;
use Maatwebsite\Excel\Concerns\ToArray;

class EntityCombinationsImport implements ToArray
{
    /**
     * @param array $rows
     * @throws BaseException
     */
    public function array(array $rows)
    {
        $a = 0;
        $combinations = [];
        foreach ($rows as $row) {
            $a++;
            if ($a = 1) continue;
            if ($row[count($row) - 2] && !$row[count($row) - 1]) {
                throw new BaseException('价格与重量必须同时填写');
            }
            array_push($combinations, [
                'id' => $row[0],
                'price' => $row[count($row) - 2],
                'weight' => $row[count($row) - 1]
            ]);
        }
        throw new BaseException(json_encode($combinations));
        Combination::updateBatch($combinations);
    }
}
