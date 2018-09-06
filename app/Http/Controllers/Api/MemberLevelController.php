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
use App\Services\Tokens\TokenFactory;

class MemberLevelController extends ApiController
{
    public function index()
    {
        $memberLevel = MemberLevelResource::collection(MemberLevel::all());

        if (TokenFactory::isAdmin()) {
            $memberLevel = [
                'data' => $memberLevel
            ];
        }

        return $this->success($memberLevel);
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
        MemberLevel::updateField($request, $memberLevel, ['name', 'accumulate_points', 'discount']);

        return $this->message('更新成功');
    }
}