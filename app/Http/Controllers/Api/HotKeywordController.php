<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/18
 * Time: 21:35
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\HotKeywordCollection;
use App\Models\HotKeyword;
use Illuminate\Http\Request;

class HotKeywordController extends ApiController
{
    public function index()
    {
        return $this->success(new HotKeywordCollection(HotKeyword::pagination()));
    }

    public function store(Request $request)
    {
        HotKeyword::create([
            'name' => $request->name,
            'url' => $request->url,
        ]);
        return $this->created();
    }

    public function update(Request $request, HotKeyword $hotKeyword)
    {
        isset($request->name) && $hotKeyword->name = $request->name;
        isset($request->url) && $hotKeyword->url = $request->url;
        isset($request->sort) && $hotKeyword->sort = $request->sort;
        isset($request->status) && $hotKeyword->status = $request->status;
        $hotKeyword->save();

        return $this->message('更新成功');
    }

    public function destroy(HotKeyword $hotKeyword)
    {
        $hotKeyword->delete();
        return $this->message('删除成功');
    }
}