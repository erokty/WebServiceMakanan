<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests;
use JWTAuth;
use Response;
use App\Repository\Transformers\MenuTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use App\Models\Menu;
use App\Models\Grouping;
use App\Models\Restaurant;

class MenuController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\ArticleTransformer
     * */
    protected $menuTransformer;

    public function __construct(
        menuTransformer $menuTransformer
    )
    {
        $this->menuTransformer = $menuTransformer;
    }

    /**
     * @description: Api Create Menu method
     * @author: Jordy Julianto
     * @param: name, price, restaurant_id, grouping_id
     * @return: Json String response
     */
    public function create(Request $request)
    {
        $rules = array (
            'name' => 'required|max:50',
            'price' => 'required|numeric',
            'restaurant_id' => 'required',
            'grouping_id' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        else{
            $menu = Menu::create([
                'name' => $request['name'],
                'price' => $request['price'],
            ]);
            $restaurant = Restaurant::findOrFail($request->restaurant_id);
            $grouping = Grouping::findOrFail($request->grouping_id);
            
            $menu->restaurant()->associate($restaurant);
            $menu->groupings()->attach($grouping->id);
            $menu->save();

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Menu created successful!',
                'data' => $this->menuTransformer->transform($menu)
            ]);
        }
    }

    /**
     * @description: Api Get Menus or Menu by id method
     * @author: Jordy Julianto
     * @param: id?
     * @return: Json String response
     */
    public function get(string $id = null)
    {
        if (!$id) {
            $menus = Menu::all();
            $transformedMenus = Collection::make(new Menu());

            foreach ($menus as $menu) {
                $transformedMenu = $this->menuTransformer->transform($menu);
                $transformedMenus->push($transformedMenu);
            }

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Menus success!',
                'data' => $transformedMenus
            ]);
        }

        $menu = Menu::findOrFail($id);
        return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Menu success!',
                'data' => $this->menuTransformer->transform($menu)
            ]);
    }

    /**
     * @description: Api Update Menu by id method
     * @author: Jordy Julianto
     * @param: id, title, content
     * @return: Json String response
     */
    public function update(string $id, Request $request)
    {
        $rules = array (
            'name' => 'required|max:20',
            'price' => 'required|numeric'
        );
        $validator = Validator::make($request->all(), $rules);

        $menu = Menu::findOrFail($id);

        if ($request->has('restaurant_id')) {
            $restaurant = Restaurant::findOrFail($request->restaurant_id);
            $menu->restaurant()->associate($restaurant);
        }
        if ($request->has('grouping_id')) {
            $grouping = Grouping::findOrFail($request->grouping_id);
            $menu->groupings()->attach($grouping);
        }

        $input = $request->all();

        $menu->fill($input)->save();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Menu updated successful!',
            'data' => $this->menuTransformer->transform($menu)
        ]);
    }

    /**
     * @description: Api Delete Menu by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);

        $menu->delete();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Menu deleted successful!'
        ]);
    }
}
