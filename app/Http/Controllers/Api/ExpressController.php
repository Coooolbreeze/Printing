<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/13
 * Time: 9:26
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\ExpressCollection;
use App\Http\Resources\ExpressResource;
use App\Models\Express;
use App\Models\ExpressRegion;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class ExpressController extends ApiController
{
    public function index(Request $request)
    {
        $expresses = ExpressResource::collection(
            (new Express())
                ->when($request->province, function ($query) use ($request) {
                    $query->whereIn('id', ExpressRegion::where('name', $request->province)
                        ->pluck('express_id')->toArray()
                    );
                })
                ->get()
        );

        if (TokenFactory::isAdmin()) {
            $expresses = [
                'data' => $expresses
            ];
        }

        return $this->success($expresses);
    }

    public function show(Express $express)
    {
        return $this->success(new ExpressResource($express));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        \DB::transaction(function () use ($request) {
            $express = Express::create([
                'name' => $request->name,
                'first_weight' => $request->first_weight,
                'additional_weight' => $request->additional_weight,
                'capped' => $request->capped
            ]);
            $regions = $this->mergeRegions($request->regions, $express->id);
            ExpressRegion::saveAll($regions);
        });
        return $this->created();
    }

    /**
     * @param Request $request
     * @param Express $express
     * @return mixed
     * @throws \Throwable
     */
    public function update(Request $request, Express $express)
    {
        \DB::transaction(function () use ($request, $express) {
            Express::updateField($request, $express, ['name', 'first_weight', 'additional_weight', 'capped']);
            if (isset($request->regions)) {
                $express->regions()->delete();
                $regions = $this->mergeRegions($request->regions, $express->id);
                ExpressRegion::saveAll($regions);
            }
        });
        return $this->message('更新成功');
    }

    public function destroy(Express $express)
    {
        $express->delete();
        return $this->message('删除成功');
    }

    private function mergeRegions($regions, $expressId)
    {
        $arr = [];
        foreach ($regions as $region) {
            array_push($arr, [
                'express_id' => $expressId,
                'name' => $region
            ]);
        }
        return $arr;
    }
}