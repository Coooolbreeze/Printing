<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/6/11
 * Time: 12:48
 */

namespace App\Http\Controllers\Api;


use App\Http\Resources\ActivityCollection;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ActivityController extends ApiController
{
    public function index()
    {
        return $this->success((new ActivityCollection(Activity::paginate(Input::get('limit') ?: 10))));
    }

    public function show(Activity $activity)
    {
        return $this->success(new ActivityResource($activity));
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        \DB::transaction(function () use ($request) {
            $activity = Activity::create([
                'image_id' => $request->image_id,
                'name' => $request->name,
                'describe' => $request->describe,
                'finished_at' => Carbon::parse(date('Y-m-d H:i:s', $request->finished_at))
            ]);
            $activity->entities()->attach($request->entities);
        });
        return $this->created();
    }

    /**
     * @param Request $request
     * @param Activity $activity
     * @return mixed
     * @throws \Throwable
     */
    public function update(Request $request, Activity $activity)
    {
        \DB::transaction(function () use ($request, $activity) {
            $activity->update([
                'image_id' => $request->image_id,
                'name' => $request->name,
                'describe' => $request->describe,
                'finished_at' => Carbon::parse(date('Y-m-d H:i:s', $request->finished_at))
            ]);
            $activity->entities()->sync($request->entities);
        });
        return $this->message('更新成功');
    }

    /**
     * @param Activity $activity
     * @return mixed
     * @throws \Throwable
     */
    public function destroy(Activity $activity)
    {
        \DB::transaction(function () use ($activity) {
            $activity->entities()->detach();
            $activity->delete();
        });
        return $this->message('删除成功');
    }
}