<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/12
 * Time: 10:06
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\LinkCollection;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LinkController extends ApiController
{
    public function index()
    {
        return $this->success(new LinkCollection(Link::pagination()));
    }

    public function show(Link $link)
    {
        return $this->success(new LinkResource($link));
    }

    public function store(Request $request)
    {
        Link::create([
            'name' => $request->name,
            'url' => $request->url
        ]);
        return $this->created();
    }

    public function update(Request $request, Link $link)
    {
        isset($request->name) && $link->name = $request->name;
        isset($request->url) && $link->url = $request->url;
        $link->save();

        return $this->message('更新成功');
    }

    public function destroy(Link $link)
    {
        $link->delete();
        return $this->message('删除成功');
    }
}