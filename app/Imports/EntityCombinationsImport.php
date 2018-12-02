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
        $combinations = [];
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if ($row[count($row) - 2] && !$row[count($row) - 1]) {
                throw new BaseException('价格与重量必须同时填写');
            }

//            Combination::where('id', $row[0])
//                ->update([
//                    'price' => (float)$row[count($row) - 2],
//                    'weight' => (float)$row[count($row) - 1]
//                ]);

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
