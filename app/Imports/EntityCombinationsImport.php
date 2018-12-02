<?php

namespace App\Imports;

use App\Exceptions\BaseException;
use App\Models\Combination;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EntityCombinationsImport implements ToArray, WithHeadingRow
{
    /**
     * @param array $rows
     * @throws BaseException
     */
    public function array(array $rows)
    {
        $combinations = [];
        foreach ($rows as $row) {
            if ($row['price'] && !$row['weight']) {
                throw new BaseException('价格与重量必须同时填写');
            }
            array_push($combinations, [
                'id' => $row['ID'],
                'price' => $row['price'],
                'weight' => $row['weight']
            ]);
        }
        Combination::updateBatch($combinations);
    }
}
