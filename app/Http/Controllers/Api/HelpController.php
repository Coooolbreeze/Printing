<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/8
 * Time: 17:17
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\HelpCollection;
use App\Http\Resources\HelpResource;
use App\Models\Help;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class HelpController extends ApiController
{
    public function index()
    {
        return $this->success(new HelpCollection(
            (new Help())
                ->when(!TokenFactory::isAdmin(), function ($query) {
                    $query->where('status', '>', 0);
                })
                ->paginate(Input::get('limit') ?: 10)
        ));
    }

    public function show(Help $help)
    {
        return $this->success(new HelpResource($help));
    }

    public function store(Request $request)
    {
        Help::create([
            'help_category_id' => $request->help_category_id,
            'title' => $request->title,
            'body' => $request->body
        ]);
        return $this->created();
    }

    public function update(Request $request, Help $help)
    {
        $help->update($request->post());
        return $this->message('更新成功');
    }

    public function destroy(Help $help)
    {
        $help->delete();
        return $this->message('删除成功');
    }

    public function batchDelete(Request $request)
    {
        Help::whereIn('id', $request->ids)
            ->delete();
        return $this->message('删除成功');
    }
}