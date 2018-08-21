<?php

namespace App\Http\Requests;


class StoreCoupon extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required',
            'quota' => 'required',
            'number' => 'required',
            'is_meanwhile' => 'required',
            'finished_at' => 'required'
        ];
    }
}
