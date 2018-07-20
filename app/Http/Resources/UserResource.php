<?php

namespace App\Http\Resources;

use App\Exceptions\BaseException;
use App\Models\MemberLevel;
use App\Services\Tokens\TokenFactory;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends Resource
{
    public static function collection($resource)
    {
        return tap(new UserResourceCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'nickname' => $this->nickname,
            'avatar' => $this->avatar,
            'sex' => $this->convertSex($this->sex),
            'account' => $this->account,
            'phone' => $this->isSelfOrAdmin() ? $this->phone : $this->partialHidden($this->phone, 3, 4),
            'email' => $this->isSelfOrAdmin() ? $this->email : $this->partialHidden($this->email, 1, 4),
            'member_level' => new MemberLevelResource($this->memberLevel),
            'accumulate_points' => $this->accumulate_points,
            'history_accumulate_points' => $this->history_accumulate_points,
            'is_bind_account' => (bool)$this->is_bind_account,
            'is_bind_phone' => (bool)$this->is_bind_phone,
            'is_bind_email' => (bool)$this->is_bind_email,
            'is_bind_wx' => (bool)$this->is_bind_wx,
            'created_at' => (string)$this->created_at
        ]);
    }

    /**
     * 隐藏部分字段
     *
     * @param $value
     * @param $start
     * @param $length
     * @return mixed
     */
    public function partialHidden($value, $start, $length)
    {
        if (!$value) {
            return $value;
        }

        return substr_replace($value, '****', $start, $length);
    }

    /**
     * 转换性别字段
     *
     * @param $value
     * @return mixed
     */
    public function convertSex($value)
    {
        $sex = [
            0 => '未知',
            1 => '男',
            2 => '女'
        ];

        return $sex[$value];
    }

    /**
     * 判断是否是自己或者超级管理员
     *
     * @return bool
     * @throws \Exception
     */
    public function isSelfOrAdmin()
    {
        try {
            return (TokenFactory::getCurrentUID() == $this->id || TokenFactory::isAdmin());
        } catch (BaseException $e) {
            return false;
        }
    }
}