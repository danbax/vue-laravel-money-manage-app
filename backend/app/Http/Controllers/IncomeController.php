<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function edit(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //
    }
}
