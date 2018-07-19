<?php

namespace App\Http\Requests;

use App\Models\Combination;
use App\Models\Entity;
use App\Models\File;
use Illuminate\Validation\Rule;

class StoreCart extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'entity_id' => [
                'required',
                Rule::in(Entity::pluck('id')->toArray())
            ],
            'file_id' => [
                'nullable',
                Rule::in(File::pluck('id')->toArray())
            ],
            'price' => 'required',
            'count' => 'nullable',
            'combination_id' => [
                'required',
                Rule::in(Combination::pluck('id')->toArray())
            ],
            'specs' => 'required'
        ];
    }
}
