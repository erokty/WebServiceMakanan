<?php

namespace App\Repository\Transformers;

class MenuTransformer extends Transformer{
    public function transform($menu){
        return [
            'id' => $menu->id,
            'name' => $menu->name,
            'price' => $menu->price,
            'restaurant_id' => $menu->restaurant_id,
            'grouping_id' => $menu->groupings()->get(),
        ];
    }
}