<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Restaurant;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Requests;
use JWTAuth;
use Response;
use App\Repository\Transformers\UserTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\UserTransformer
     * */
    protected $userTransformer;

    public function __construct(userTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    /**
     * @description: Api user authenticate method
     * @author: Adelekan David Aderemi
     * @param: email, password
     * @return: Json String response
     */
    public function authenticate(Request $request)
    {
        $rules = array (
            'email' => 'required|email',
            'password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        else{
            $user = User::where('email', $request['email'])->first();
            if($user){
                $api_token = $user->api_token;
                if ($api_token == NULL){
                    return $this->_login($request['email'], $request['password']);
                }
                try{
                    $user = JWTAuth::toUser($api_token);
                    return $this->respond([
                        'status' => 'success',
                        'status_code' => $this->getStatusCode(),
                        'message' => 'Already logged in',
                        'user' => $this->userTransformer->transform($user)
                    ]);
                }catch(JWTException $e){
                    $user->api_token = NULL;
                    $user->save();
                    return $this->respondInternalError("Login Unsuccessful. An error occurred while performing an action!");
                }
            }
            else{
                return $this->respondWithError("Invalid Email or Password");
            }
        }
    }

    private function _login($email, $password)
    {
        $credentials = ['email' => $email, 'password' => $password];

        if ( ! $token = JWTAuth::attempt($credentials)) {
            return $this->respondWithError("User does not exist!");
        }
        $user = JWTAuth::toUser($token);
        $user->api_token = $token;
        $user->save();
        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Login successful!',
            'data' => $this->userTransformer->transform($user)
        ]);
    }

    /**
     * @description: Api user register method
     * @author: Adelekan David Aderemi
     * @param: lastname, firstname, username, email, password
     * @return: Json String response
     */
    public function register(Request $request)
    {
        $rules = array (
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'address' => 'required|min:1|max:255',
            'birthdate' => 'required|min:1|max:15|date',
            'phone_number' => 'required|numeric',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        else{
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => \Hash::make($request['password']),
                'address' => $request['address'],
                'birthdate' => Carbon::createFromFormat('d-m-Y', $request['birthdate']),
                'phone_number' => $request['phone_number'],
                'is_admin' => true,

            ]);
            return $this->_login($request['email'], $request['password']);
        }
    }

    /**
     * @description: Api user logout method
     * @author: Adelekan David Aderemi
     * @param: api_token
     * @return: Json String response
     */
    public function logout($api_token)
    {
        try{
            $user = JWTAuth::toUser($api_token);
            $user->api_token = NULL;
            $user->save();
            JWTAuth::setToken($api_token)->invalidate();
            $this->setStatusCode(Res::HTTP_OK);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Logout successful!',
            ]);

        }catch(JWTException $e){
            return $this->respondInternalError("An error occurred while performing an action!");
        }
    }

    /**
     * @description: Api get current user method
     * @author: Jordy Julianto
     * @param: null
     * @return: Json String response
     */
    public function getCurrentUser()
    {
        $user = Auth::user();
        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Get Current User successful!',
            'data' => $this->userTransformer->transform($user)
        ]);
    }

    /**
     * @description: Api get users method
     * @author: Jordy Julianto
     * @param: null
     * @return: Json String response
     */
    public function getUsers()
    {
        $users = User::paginate(10);

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Get Current User successful!',
            'data' => $users
        ]);
    }

    /**
     * @description: Api Delete User by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'User deleted successful!'
        ]);
    }

    /**
     * @description: Api Browse Restaurant or Menu by value method
     * @author: Jordy Julianto
     * @param: browsed
     * @return: Json String response
     */
    public function browse(string $selected, string $browsed)
    {
        switch ($selected) {
            case 'restaurant':
                $model = Restaurant::where('name', 'LIKE', $browsed.'%')
                         ->with('menus')
                         ->with('reviews')
                         ->get();
                break;
            case 'food':
                $model = Menu::where('name', 'LIKE', $browsed.'%')
                         ->with('restaurant')
                         ->with('groupings')
                         ->get();
                break;
            default:
                break;
        }

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Browsed '.$selected.' successful!',
            'data' => $model
        ]);
    }
}