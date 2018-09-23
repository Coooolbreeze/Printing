<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 16:15
 */

namespace App\Http\Resources;


use App\Models\Entity;
use App\Services\Tokens\TokenFactory;

class SecondaryTypeResource extends Resource
{
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'entities' => $this->when(!TokenFactory::isAdmin(),
                EntityResource::collection(
                    $this->entities()
                        ->when($request->entity_id, function ($query) use ($request) {
                            $query->where('id', '<>', $request->entity_id);
                        })
                        ->get()
                )->show(['id', 'image', 'name', 'type', 'summary', 'status', 'sales', 'price', 'comment_count'])
            )
        ]);
    }
}