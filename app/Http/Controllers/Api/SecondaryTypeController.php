<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/16
 * Time: 12:27
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Resources\SecondaryTypeResource;
use App\Models\Entity;
use App\Models\SecondaryType;
use App\Models\Type;
use Illuminate\Http\Request;

class SecondaryTypeController extends ApiController
{
    public function index(Request $request)
    {
        return $this->success([
            'data' => SecondaryTypeResource::collection(SecondaryType::where('type_id', $request->type_id)->get())
        ]);
    }

    public function store(Request $request)
    {
        $secondaryType = SecondaryType::create([
            'type_id' => $request->type_id,
            'name' => $request->name
        ]);

        $type = Type::find($request->type_id);

        if ($type->secondaryTypes()->count() == 1) {
            $type->entities()->update([
                'secondary_type_id' => $secondaryType->id
            ]);
        }

        return $this->created();
    }

    public function update(Request $request, SecondaryType $secondaryType)
    {
        SecondaryType::updateField($request, $secondaryType, ['name']);

        return $this->message('更新成功');
    }

    public function destroy(SecondaryType $secondaryType)
    {
        if ($secondaryType->entities()->count() > 0) {
            $secondaryTypes = Type::find($secondaryType->type_id)->secondaryTypes;
            if($secondaryTypes->count() > 1) {
                $secondaryTypeId = $secondaryTypes->where('id', '<>', $secondaryType->id)->first()->id;
                $secondaryType->entities()->update([
                    'secondary_type_id' => $secondaryTypeId
                ]);
            } else {
                $secondaryType->entities()->update([
                    'secondary_type_id' => null
                ]);
            }
        }

        $secondaryType->delete();

        return $this->message('删除成功');
    }
}