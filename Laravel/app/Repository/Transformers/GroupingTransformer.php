<?php

namespace App\Repository\Transformers;

class GroupingTransformer extends Transformer{
    public function transform($grouping){
        return [
            'id' => $grouping->id,
            'name' => $grouping->name,
        ];
    }
}