<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests;
use JWTAuth;
use Response;
use App\Repository\Transformers\GroupingTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use App\Models\Grouping;

class GroupingController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\GroupingTransformer
     * */
    protected $groupingTransformer;

    public function __construct(groupingTransformer $groupingTransformer)
    {
        $this->groupingTransformer = $groupingTransformer;
    }

    /**
     * @description: Api Create Grouping method
     * @author: Jordy Julianto
     * @param: name
     * @return: Json String response
     */
    public function create(Request $request)
    {
        $rules = array (
            'name' => 'required|max:45',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError('Fields Validation Failed.', $validator->errors());
        }
        else{
            $grouping = Grouping::create([
                'name' => $request['name'],

            ]);
            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Grouping created successful!',
                'data' => $this->groupingTransformer->transform($grouping)
            ]);
        }
    }

    /**
     * @description: Api Get Groupings or Grouping by id method
     * @author: Jordy Julianto
     * @param: id?
     * @return: Json String response
     */
    public function get(string $id = null)
    {
        if (!$id) {
            $groupings = Grouping::all();
            $transformedGroupings = Collection::make(new Grouping());

            foreach ($groupings as $grouping) {
                $transformedGrouping = $this->groupingTransformer->transform($grouping);
                $transformedGroupings->push($transformedGrouping);
            }

            return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Groupings success!',
                'data' => $transformedGroupings
            ]);
        }

        $grouping = Grouping::findOrFail($id);
        return $this->respond([
                'status' => 'success',
                'status_code' => $this->getStatusCode(),
                'message' => 'Get Grouping success!',
                'data' => $this->groupingTransformer->transform($grouping)
            ]);
    }

    /**
     * @description: Api Update Grouping by id method
     * @author: Jordy Julianto
     * @param: id, name
     * @return: Json String response
     */
    public function update(string $id, Request $request)
    {
        $rules = array (
            'name' => 'required|max:45',
        );
        $validator = Validator::make($request->all(), $rules);

        $grouping = Grouping::findOrFail($id);

        $input = $request->all();

        $grouping->fill($input)->save();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Grouping updated successful!',
            'data' => $this->groupingTransformer->transform($grouping)
        ]);
    }

    /**
     * @description: Api Delete Grouping by id method
     * @author: Jordy Julianto
     * @param: id
     * @return: Json String response
     */
    public function destroy(string $id)
    {
        $grouping = Grouping::findOrFail($id);

        $grouping->delete();

        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'message' => 'Grouping deleted successful!'
        ]);
    }
}
