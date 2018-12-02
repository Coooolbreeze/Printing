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
            array_push($combinations, [
                'id' => (int)str_replace('/t', '', $row[0]),
                'price' => $row[count($row) - 2],
                'weight' => $row[count($row) - 1]
            ]);
        }
        Combination::updateBatch($combinations);
    }
}
