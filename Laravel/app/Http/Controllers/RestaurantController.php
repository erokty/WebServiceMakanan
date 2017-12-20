<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests;
use JWTAuth;
use Response;
use App\Repository\Transformers\RestaurantTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use App\Models\Restaurant;

class RestaurantController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\RestaurantTransformer
     * */
    protected $restaurantTransformer;

    public function __construct(restaurantTransformer $restaurantTransformer)
    {
        $this->restaurantTransformer = $restaurantTransformer;
    }

    /**
     * @description: Api Create Restaurant method
     * @author: Jordy Julianto
     * @param: name, description, image_url?
     * @return: Json String response
     */
    public function create(Request $request)
    {
        $rules = array (
            'name' => 'required|max:45',
            'description' => 'required|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        else{
            $fileName = $this->uploadFileToStorage($request);
            
            $restaurant = Restaurant::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'file_name' => $fileName,

            ]);

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Restaurant created successful!',
                'data' => $this->restaurantTransformer->transform($restaurant)
            ]);
        }
    }

    /**
     * @description: Api Get Restaurants or Restaurant by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function get(string $id = null)
    {
        if (!$id) {
            $restaurants = Restaurant::paginate(10);
            // $transformedRestaurants = Collection::make(new Restaurant());

            // foreach ($restaurants as $restaurant) {
            //     $transformedRestaurant = $this->restaurantTransformer->transform($restaurant);
            //     $transformedRestaurants->push($transformedRestaurant);
            // }

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Restaurants success!',
                'data' => $restaurants
            ]);
        }

        $restaurant = Restaurant::findOrFail($id);
        return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Restaurant success!',
                'data' => $restaurant
            ]);
    }

    /**
     * @description: Api Update Restaurant by id method
     * @author: Jordy Julianto
     * @param: id, name, description, image_url?
     * @return: Json String response
     */
    public function update(string $id, Request $request)
    {
        $rules = array (
            'name' => 'required|max:45',
            'description' => 'required|max:255',
            'image_url' => 'max:255',
        );
        $validator = Validator::make($request->all(), $rules);

        $restaurant = Restaurant::findOrFail($id);

        $input = $request->all();

        $restaurant->fill($input)->save();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Restaurant updated successful!',
            'data' => $this->restaurantTransformer->transform($restaurant)
        ]);
    }

    /**
     * @description: Api Delete Restaurant by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function destroy(string $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $restaurant->delete();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Restaurant deleted successful!'
        ]);
    }
}
