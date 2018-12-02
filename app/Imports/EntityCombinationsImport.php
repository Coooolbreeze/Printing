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
            if ($row['价格(元)'] && !$row['重量(g)']) {
                throw new BaseException('价格与重量必须同时填写');
            }
            array_push($combinations, [
                'id' => $row['ID'],
                'price' => $row['价格(元)'],
                'weight' => $row['重量(g)']
            ]);
        }
        Combination::updateBatch($combinations);
    }
}
