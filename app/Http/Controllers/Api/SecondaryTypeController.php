<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/16
 * Time: 12:27
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Models\SecondaryType;
use Illuminate\Http\Request;

class SecondaryTypeController extends ApiController
{
    public function store(Request $request)
    {
        SecondaryType::create([
            'type_id' => $request->type_id,
            'name' => $request->name
        ]);

        return $this->created();
    }

    public function update(Request $request, SecondaryType $secondaryType)
    {
        SecondaryType::updateField($request, $secondaryType, ['name']);

        return $this->message('更新成功');
    }

    public function destroy(SecondaryType $secondaryType)
    {
        if ($secondaryType->entities()->count() > 0)
            throw new BaseException('该类型下已有商品，无法删除');

        $secondaryType->delete();

        return $this->message('删除成功');
    }
}