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

class ActivityController extends ApiController
{
    public function index()
    {
        return $this->success(new ActivityCollection(Activity::pagination()));
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
            isset($request->image_id) && $activity->image_id = $request->image_id;
            isset($request->name) && $activity->name = $request->name;
            isset($request->describe) && $activity->describe = $request->describe;
            isset($request->finished_at) && $activity->finished_at = Carbon::parse(date('Y-m-d H:i:s', $request->finished_at));
            isset($request->entities) && $activity->entities()->sync($request->entities);
            $activity->save();
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