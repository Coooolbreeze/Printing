<?php
/**
 * Created by PhpStorm.
 * User: 392113643
 * Date: 2018/8/3
 * Time: 16:59
 */

namespace App\Http\Resources;


use App\Models\LargeCategoryItem;

class CategoryItemResource extends Resource
{
    public function toArray($request)
    {
        if ($this->item_type == 1 || $this->entity->status == 1) {
            return $this->filterFields([
                'id' => $this->id,
                'category' => (new CategoryResource($this->category))->show(['id', 'name']),
                'type' => $this->item_type,
                'item' => $this->item_type == 1
                    ? (new TypeResource($this->type))->show(['id', 'name'])
                    : (new EntityResource($this->entity))->show(['id', 'name']),
                'is_primary' => $this->isPrimary(),
                'is_hot' => (bool)$this->is_hot,
                'is_new' => (bool)$this->is_new
            ]);
        }
    }

    public function isPrimary(): bool
    {
        $largeItem = LargeCategoryItem::where('item_type', $this->item_type)
            ->where('item_id', $this->item_id)
            ->first();

        return (bool)$largeItem;
    }
}