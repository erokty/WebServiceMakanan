<?php

namespace App\Repository\Transformers;

class ArticlePictureTransformer extends Transformer{
    public function transform($article){
        return [
            'id' => $article->id,
            'file_name' => $article->file_name,
            'article_id' => $article->article_id,
        ];
    }
}