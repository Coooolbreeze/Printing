<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/20
 * Time: 18:11
 */

namespace App\Http\Controllers\Api;


use App\Http\Requests\StoreMemberLevel;
use App\Http\Requests\UpdateMemberLevel;
use App\Http\Resources\MemberLevelResource;
use App\Models\MemberLevel;

class MemberLevelController extends ApiController
{
    public function index()
    {
        return $this->success(MemberLevelResource::collection(MemberLevel::all()));
    }

    public function store(StoreMemberLevel $request)
    {
        MemberLevel::create([
            'name' => $request->name,
            'accumulate_points' => $request->accumulate_points,
            'discount' => $request->discount
        ]);

        return $this->created();
    }

    public function update(UpdateMemberLevel $request, MemberLevel $memberLevel)
    {
        isset($request->name) && $memberLevel->name = $request->name;
        isset($request->accumulate_points) && $memberLevel->accumulate_points = $request->accumulate_points;
        isset($request->discount) && $memberLevel->discount = $request->discount;
        $memberLevel->save();

        return $this->message('更新成功');
    }
}