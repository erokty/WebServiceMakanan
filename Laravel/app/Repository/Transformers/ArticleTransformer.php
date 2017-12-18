<?php

namespace App\Repository\Transformers;

class ArticleTransformer extends Transformer{
    public function transform($article){
        return [
            'id' => $article->id,
            'title' => $article->title,
            'content' => $article->content,
            'file_name' => $article->file_name,
            'user_id' => $article->user_id,
        ];
    }
}