<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLastRecords(Request $request)
    {
        if(!UserController::isValidToken($request)){
            $message = "The token is invalid";
            $code = 403;
            return ApiController::errorResponse($message,$code);
        }
        $user = UserController::getUser($request);

        $records = Record::where("user_id",$request->get("clientId"))->orderBy('id', 'desc')->take(5)->get();
        if($records){
            return ApiController::successResponse($records);
        }else{
            $message = "תקלה בבסיס הנתונים";
            return ApiController::errorResponse($message);
        }
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoriesSum(Request $request)
    {
        if(!UserController::isValidToken($request)){
            $message = "The token is invalid";
            $code = 403;
            return ApiController::errorResponse($message,$code);
        }
        $user = UserController::getUser($request);

        $categories = Record::where("user_id",$request->get("clientId"))
        ->groupBy('category')
        ->selectRaw('sum(amount) as sum, category')
        ->orderBy("sum","desc")
        ->get();

        if($categories){
            return ApiController::successResponse($categories);
        }else{
            $message = "תקלה בבסיס הנתונים";
            return ApiController::errorResponse($message);
        }
    }

    /**
     * add new record.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!UserController::isValidToken($request)){
            $message = "The token is invalid";
            $code = 403;
            return ApiController::errorResponse($message,$code);
        }
        $user = UserController::getUser($request);


        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            
            $message = "יש למלא סכום ותאריך";
            return ApiController::errorResponse($message);
        }
        $category =  "";
        if($request->has("category")){
            $category = $request->get("category");
        }

        $isSuccess = Record::create([
        'amount'      => $request->get('amount'),
        'date'      => $request->get('date'),
        'category'      => $category,
        'user_id'     => $user->id
        ]);



        if($isSuccess){
            $user->current_amount-= $request->get("amount");
            $user->save();

            $record = Record::all()->last();
            
            return ApiController::successResponse($record);
        }else{
            $message = "תקלה בבסיס הנתונים";
            return ApiController::errorResponse($message);
        }
    }
}
