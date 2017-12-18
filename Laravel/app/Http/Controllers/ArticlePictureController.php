<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests;
use JWTAuth;
use Response;
use App\Repository\Transformers\ArticlePictureTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use App\Models\Article;
use App\Models\ArticlePicture;

class ArticlePictureController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\ArticleTransformer
     * */
    protected $articlePictureTransformer;

    public function __construct(
        articlePictureTransformer $articlePictureTransformer
    )
    {
        $this->articlePictureTransformer = $articlePictureTransformer;
    }

    /**
     * @description: Api Upload Article's image by id method
     * @author: Jordy Julianto
     * @param: id, file
     * @return: Json String response
     */
    public function upload(string $id, Request $request)
    {
        $article = Article::findOrFail($id);

        $rules = array (
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        else{
            $fileName = $this->uploadFileToStorage($request);
            $articlePic = ArticlePicture::create([
                'file_name' => $fileName,
                'article_id' => $article->id
            ]);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Article\'s Picture uploaded successful!',
                'data' => $this->articlePictureTransformer->transform($articlePic)
            ]);
        }
    }

    /**
     * @description: Api Get ArticlePictures by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function get(string $articleId)
    {
        $article = Article::findOrFail($articleId);
        $articlePics = ArticlePicture::where('article_id', $article->id)->get();
        $transformedArticlePics = Collection::make(new ArticlePicture());

        foreach ($articlePics as $articlePic) {
          $transformedArticlePic = $this->articlePictureTransformer->transform($articlePic);
          $transformedArticlePics->push($transformedArticlePic);
        }

        return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Article\'s Picture success!',
                'data' => $transformedArticlePics
            ]);
    }

    /**
     * @description: Api Delete Article's Picture by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function destroy(string $id)
    {
        $articlePic = ArticlePicture::findOrFail($id);

        $articlePic->delete();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Article\'s Picture deleted successful!'
        ]);
    }
}
