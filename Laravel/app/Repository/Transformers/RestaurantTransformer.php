<?php

namespace App\Repository\Transformers;

class RestaurantTransformer extends Transformer{
    public function transform($restaurant){
        return [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'description' => $restaurant->description,
            'file_name' => $restaurant->file_name,
        ];
    }
}