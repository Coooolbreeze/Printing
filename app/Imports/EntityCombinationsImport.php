<?php

namespace App\Imports;

use App\Combination;
use Maatwebsite\Excel\Concerns\ToModel;

class EntityCombinationsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Combination([
            //
        ]);
    }
}
