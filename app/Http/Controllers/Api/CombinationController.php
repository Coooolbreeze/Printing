<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/19
 * Time: 16:49
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Exports\EntityCombinationsExport;
use App\Http\Resources\CombinationCollection;
use App\Http\Resources\CombinationResource;
use App\Models\Combination;
use App\Models\Entity;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CombinationController extends ApiController
{
    public function index(Request $request)
    {
        return $this->success(new CombinationCollection(
            Combination::where('entity_id', $request->entity_id)->paginate(Combination::getLimit())
        ));
    }

    public function update(Request $request, Combination $combination)
    {
        Combination::updateField($request, $combination, ['price', 'weight']);

        return $this->message('更新成功');
    }

    public function export(Request $request)
    {
        return Excel::download(new EntityCombinationsExport($request->entity_id), Entity::find($request->entity_id)->name . '.xlsx');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\BaseException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function import(Request $request)
    {
        $spreadsheet = (new Xlsx())->load($request->file('file'))->getActiveSheet();

        $data = $spreadsheet->rangeToArray('A2:' . $spreadsheet->getHighestColumn() . $spreadsheet->getHighestRow());

        $combinations = [];
        foreach ($data as $entity) {
            $price = $entity[count($entity) - 2];
            $weight = $entity[count($entity) - 1];

            if ($price && !$weight) {
                throw new BaseException('价格与重量必须同时填写');
            }

            array_push($combinations, [
                'id' => $entity[0],
                'price' => $price,
                'weight' => $weight
            ]);
        }
        Combination::updateBatch($combinations);

        return $this->message('更新成功');
    }
}