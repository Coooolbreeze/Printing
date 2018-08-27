<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/18
 * Time: 21:35
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\HotKeywordCollection;
use App\Http\Resources\HotKeywordResource;
use App\Models\HotKeyword;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class HotKeywordController extends ApiController
{
    public function index()
    {
        if (TokenFactory::isAdmin()) {
            $hotKeywords = new HotKeywordCollection(HotKeyword::pagination());
        }else {
            $hotKeywords = HotKeywordResource::collection(HotKeyword::all());
        }

        return $this->success($hotKeywords);
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
        HotKeyword::updateField($request, $hotKeyword, ['name', 'url', 'sort', 'status']);

        return $this->message('更新成功');
    }

    public function destroy(HotKeyword $hotKeyword)
    {
        $hotKeyword->delete();
        return $this->message('删除成功');
    }

    public function batchDestroy(Request $request)
    {
        HotKeyword::whereIn('id', $request->ids)
            ->delete();
        return $this->message('删除成功');
    }
}