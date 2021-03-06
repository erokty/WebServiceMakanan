<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests;
use JWTAuth;
use Response;
use App\Repository\Transformers\ArticleTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use App\Models\Article;
use App\User;

class ArticleController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\ArticleTransformer
     * */
    protected $articleTransformer;

    public function __construct(
        articleTransformer $articleTransformer
    )
    {
        $this->articleTransformer = $articleTransformer;
    }

    /**
     * @description: Api Create Article method
     * @author: Jordy Julianto
     * @param: title, content
     * @return: Json String response
     */
    public function create(Request $request)
    {
        $rules = array (
            'title' => 'required|max:20',
            'content' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        else{
            $article = Article::create([
                'title' => $request['title'],
                'content' => $request['content'],
            ]);

            $user = User::findOrFail(Auth::user()->id);
            $article->user()->associate($user);
            $article->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Article created successful!',
                'data' => $this->articleTransformer->transform($article)
            ]);
        }
    }

    /**
     * @description: Api Get Articles or Article by id method
     * @author: Jordy Julianto
     * @param: id?
     * @return: Json String response
     */
    public function get(string $id = null)
    {
        if (!$id) {
            if (Auth::user()->is_admin) {
                $articles = Article::with('user')->paginate(10);
            } else {
                $articles = Article::where('user_id', Auth::user()->id)->with('user')->paginate(10);
            }

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Articles success!',
                'data' => $articles
            ]);
        }

        $article = Article::findOrFail($id)->with('user');
        return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Article success!',
                'data' => $article
            ]);
    }

    /**
     * @description: Api Update Article by id method
     * @author: Jordy Julianto
     * @param: id, title, content
     * @return: Json String response
     */
    public function update(string $id, Request $request)
    {
        $rules = array (
            'title' => 'required|max:20',
            'content' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        $article = Article::findOrFail($id);

        $input = $request->all();

        $article->fill($input)->save();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Article updated successful!',
            'data' => $this->articleTransformer->transform($article)
        ]);
    }

    /**
     * @description: Api Delete Article by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        $article->delete();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Article deleted successful!'
        ]);
    }
}