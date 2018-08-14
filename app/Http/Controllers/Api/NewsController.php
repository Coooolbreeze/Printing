<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/5
 * Time: 15:00
 */

namespace App\Http\Controllers\Api;


use App\Enum\NewsStatusEnum;
use App\Http\Requests\StoreNews;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class NewsController extends ApiController
{
    public function index(Request $request)
    {
        $news = (new News())
            ->when(!TokenFactory::isAdmin(), function ($query) {
                $query->where('status', '<>', NewsStatusEnum::UNPUBLISHED);
            })
            ->when($request->title, function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->title . '%');
            })
            ->paginate(News::getLimit());

        return $this->success(new NewsCollection($news));
    }

    public function show(News $news)
    {
        return $this->success(new NewsResource($news));
    }

    public function store(StoreNews $request)
    {
        News::create([
            'news_category_id' => $request->news_category_id,
            'image_id' => $request->image_id,
            'title' => $request->title,
            'from' => $request->from,
            'summary' => interceptHTML($request->body),
            'body' => $request->body
        ]);
        return $this->created();
    }

    public function update(StoreNews $request, News $news)
    {
        isset($request->image_id) && $news->image_id = $request->image_id;
        isset($request->title) && $news->title = $request->title;
        isset($request->from) && $news->from = $request->from;
        isset($request->summary) && $news->summary = $request->summary;
        isset($request->body) && $news->body = $request->body;
        $news->save();

        return $this->message('更新成功');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return $this->message('删除成功');
    }

    public function batchDestroy(Request $request)
    {
        News::whereIn('id', $request->ids)
            ->delete();
        return $this->message('删除成功');
    }
}