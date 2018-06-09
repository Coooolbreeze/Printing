<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/5
 * Time: 9:44
 */

namespace App\Exports;


use App\Models\Attribute;
use App\Models\Combination;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EntityCombinationsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $entityId;

    public function __construct($entityId)
    {
        $this->entityId = $entityId;
    }

    public function query()
    {
        return Combination::query()->where('entity_id', $this->entityId)
            ->select('combination');
    }

    public function map($row): array
    {
        return explode('|', $row->combination);
    }

    public function headings(): array
    {
        $attr = Attribute::where('entity_id', $this->entityId)
            ->pluck('name')
            ->toArray();
        array_push($attr, '价格');
        array_push($attr, '重量');
        return $attr;
    }
}