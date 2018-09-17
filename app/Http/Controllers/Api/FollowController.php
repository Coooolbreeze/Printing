<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/9/18
 * Time: 2:52
 */

namespace App\Http\Controllers\Api;


use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class FollowController  extends ApiController
{
    public function store(Request $request)
    {
        TokenFactory::getCurrentUser()->entities()->attach($request->entity_id);

        return $this->message('关注成功');
    }

    public function destroy(Request $request)
    {
        TokenFactory::getCurrentUser()->entities()->detach($request->entity_id);

        return $this->message('取消关注成功');
    }
}