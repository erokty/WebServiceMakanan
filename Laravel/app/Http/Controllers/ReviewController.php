<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests;
use JWTAuth;
use Response;
use App\Repository\Transformers\ReviewTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use App\Models\Review;
use App\Models\Restaurant;

class ReviewController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\ReviewTransformer
     * */
    protected $reviewTransformer;

    public function __construct(
        reviewTransformer $reviewTransformer
    )
    {
        $this->reviewTransformer = $reviewTransformer;
    }

    /**
     * @description: Api Create Review method
     * @author: Jordy Julianto
     * @param: content, rating, restaurant_id
     * @return: Json String response
     */
    public function create(Request $request)
    {
        $rules = array (
            'content' => 'required|max:255',
            'rating' => 'required|numeric',
            'restaurant_id'=> 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        else{
            $restaurant = Restaurant::findOrFail($request->restaurant_id);
            $review = Review::create([
                'content' => $request['content'],
                'rating' => $request['rating'],
                'user_id' => Auth::user()->id,
            ]);

            $review->restaurant()->associate($restaurant);
            $review->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Review created successful!',
                'data' => $this->reviewTransformer->transform($review)
            ]);
        }
    }

    /**
     * @description: Api Get Reviews or Review by id method
     * @author: Jordy Julianto
     * @param: id?
     * @return: Json String response
     */
    public function get(string $id = null)
    {
        if (!$id) {
            $reviews = Review::all();
            $transformedReviews = Collection::make(new Review());

            foreach ($reviews as $review) {
                $transformedReview = $this->reviewTransformer->transform($review);
                $transformedReviews->push($transformedReview);
            }

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Reviews success!',
                'data' => $transformedReviews
            ]);
        }

        $review = Review::findOrFail($id);
        return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Review success!',
                'data' => $this->reviewTransformer->transform($review)
            ]);
    }

    /**
     * @description: Api Update Review by id method
     * @author: Jordy Julianto
     * @param: id, content, rating, restaurant_id
     * @return: Json String response
     */
    public function update(string $id, Request $request)
    {
        $rules = array (
            'content' => 'max:255',
            'rating' => 'numeric',
        );
        $validator = Validator::make($request->all(), $rules);

        $review = Review::findOrFail($id);
        if ($request->has('restaurant_id')) {
            $restaurant = Restaurant::findOrFail($request->restaurant_id);
            $review->restaurant()->associate($restaurant);
        }

        $input = $request->all();

        $review->user_id = Auth::user()->id;
        $review->fill($input)->save();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Review updated successful!',
            'data' => $this->reviewTransformer->transform($review)
        ]);
    }

    /**
     * @description: Api Delete Review by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);

        $review->delete();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Review deleted successful!'
        ]);
    }
}
