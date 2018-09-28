<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/18
 * Time: 2:52
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Models\Entity;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class FollowController  extends ApiController
{
    /**
     * @param Request $request
     * @return mixed
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public function store(Request $request)
    {
        $entityIds = $request->entity_id;

        if (!is_array($entityIds)) $entityIds = [$entityIds];

        $entities = Entity::pluck('id')->toArray();
        if (array_intersect($entityIds, $entities) != $entityIds) {
            throw new BaseException('列表中有不存在的商品');
        }

        $followed = TokenFactory::getCurrentUser()->entities()->pluck('id')->toArray();

        collect($entityIds)->filter(function ($id) use ($followed) {
            return !in_array($id, $followed);
        });

//        if (array_intersect($entityIds, $followed)) {
//            throw new BaseException('列表中有存在已关注的商品');
//        }

        TokenFactory::getCurrentUser()->entities()->attach($entityIds);

        return $this->message('关注成功');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Exceptions\TokenException
     */
    public function destroy(Request $request)
    {
        TokenFactory::getCurrentUser()->entities()->detach($request->entity_id);

        return $this->message('取消关注成功');
    }
}