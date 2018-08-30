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
use App\Http\Requests\UpdateNews;
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
            ->latest()
            ->paginate(News::getLimit());

        return $this->success(new NewsCollection($news));
    }

    public function recommend()
    {
        return $this->success([
            'relevance' => (NewsResource::collection(News::inRandomOrder()->limit(4)->get()))->hide(['body']),
            'new' => (NewsResource::collection(News::latest()->limit(4)->get()))->hide(['body'])
        ]);
    }

    public function other(Request $request)
    {
        return $this->success(
            NewsResource::collection(
                News::whereNotIn('id', [$request->id])
                    ->latest()->limit(5)->get()
            )->hide(['body'])
        );
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

    public function update(UpdateNews $request, News $news)
    {
        News::updateField($request, $news, ['news_category_id', 'image_id', 'title', 'from', 'summary', 'body', 'status']);

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