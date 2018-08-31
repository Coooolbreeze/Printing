<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/2
 * Time: 14:50
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\ForbiddenException;
use App\Exceptions\TokenException;
use App\Http\Requests\StoreMessage;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\MemberLevel;
use App\Models\Message;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Request;

class MessageController extends ApiController
{
    public function index(Request $request)
    {
        $messages = (new Message())
            ->when($request->is_read, function ($query) use ($request) {
                $query->where('is_read', $request->is_read);
            })
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->member_level_id, function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('member_level_id', $request->member_level_id);
                });
            })
            ->latest()
            ->paginate(Message::getLimit());

        return $this->success(new MessageCollection($messages));
    }

    public function show(Message $message)
    {
        return $this->success(new MessageResource($message));
    }

    /**
     * @param StoreMessage $request
     * @return mixed
     * @throws TokenException
     */
    public function store(StoreMessage $request)
    {
        $ids = [];

        if ($request->ids) {
            $ids = $request->ids;
        }

        if ($request->members) {
            foreach ($request->members as $member) {
                $ids = array_merge($ids, MemberLevel::find($member)->users()->pluck('id')->toArray());
            }
        }

        if (count($ids) > 1) {
            $ids = array_unique($ids);
        }

        Message::send($ids, $request->title, $request->body, TokenFactory::getCurrentUser()->nickname);

        return $this->created();
    }

    /**
     * @param Request $request
     * @param Message $message
     * @return mixed
     * @throws ForbiddenException
     * @throws TokenException
     */
    public function update(Request $request, Message $message)
    {
        if (!TokenFactory::isSelfOrAdmin($message->user_id))
            throw new ForbiddenException();

        $message->update(['is_read' => 1]);

        return $this->message('更新成功');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws TokenException
     */
    public function batchUpdate(Request $request)
    {
        $ids = $request->ids;

        if ($ids == 'all') {
            TokenFactory::getCurrentUser()->messages()->update(['is_read' => 1]);
        } else {
            Message::whereIn('id', $ids)->each(function ($message) {
                if (!TokenFactory::isSelfOrAdmin($message->user_id))
                    throw new ForbiddenException();
            });
            Message::whereIn('id', $ids)->update(['is_read' => 1]);
        }

        return $this->message('更新成功');
    }

    /**
     * @param Message $message
     * @return mixed
     * @throws ForbiddenException
     * @throws TokenException
     */
    public function destroy(Message $message)
    {
        if (!TokenFactory::isSelfOrAdmin($message->user_id))
            throw new ForbiddenException();

        $message->delete();

        return $this->message('删除成功');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function batchDestroy(Request $request)
    {
        $ids = $request->ids;

        if ($ids == 'all') {
            TokenFactory::getCurrentUser()->messages()->delete();
        } else {
            Message::whereIn('id', $ids)->each(function ($message) {
                if (!TokenFactory::isSelfOrAdmin($message->user_id))
                    throw new ForbiddenException();
            });
            Message::whereIn('id', $ids)->delete();
        }

        return $this->message('删除成功');
    }
}