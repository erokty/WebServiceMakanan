<?php

namespace App\Repository\Transformers;

class ReviewTransformer extends Transformer{
    public function transform($review){
        return [
            'id' => $review->id,
            'content' => $review->content,
            'rating' => $review->rating,
            'restaurant' => $review->restaurant()->get(),
        ];
    }
}