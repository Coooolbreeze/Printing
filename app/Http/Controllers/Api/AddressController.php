<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/7/24
 * Time: 16:00
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\BaseException;
use App\Http\Requests\StoreAddress;
use App\Http\Requests\UpdateAddress;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\Tokens\TokenFactory;

class AddressController extends ApiController
{
    public function show(Address $address)
    {
        return $this->success(new AddressResource($address));
    }

    /**
     * @param StoreAddress $request
     * @return mixed
     * @throws \Throwable
     */
    public function store(StoreAddress $request)
    {
        \DB::transaction(function () use ($request) {
            $address = Address::create([
                'user_id' => TokenFactory::getCurrentUID(),
                'name' => $request->name,
                'phone' => $request->phone,
                'province' => $request->province,
                'city' => $request->city,
                'county' => $request->county,
                'detail' => $request->detail
            ]);
            $request->is_default && Address::setDefault($address->id);
        });

        return $this->created();
    }

    /**
     * @param UpdateAddress $request
     * @param Address $address
     * @return mixed
     * @throws BaseException
     * @throws \App\Exceptions\TokenException
     */
    public function update(UpdateAddress $request, Address $address)
    {
        if (!TokenFactory::isValidOperate($address->user_id) && !TokenFactory::isAdmin())
            throw new BaseException('不能修改别人的地址');

        Address::updateField($request, $address, ['name', 'phone', 'province', 'city', 'county', 'detail']);

        $request->is_default && Address::setDefault($address->id);

        return $this->message('更新成功');
    }

    /**
     * @param Address $address
     * @throws \Exception
     */
    public function destroy(Address $address)
    {
        $address->delete();
        $this->message('删除成功');
    }
}