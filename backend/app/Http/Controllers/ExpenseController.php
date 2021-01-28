<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class ExpenseController extends Controller
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
     * Show the form for creating a new resource.
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
            'name' => 'required',
            'amount' => 'required',
            'date' => 'required'
        ]);

        if ($validator->fails()) {
            $message = "יש למלא את כל הפרטים";
            return ApiController::errorResponse($message);
        }

        $isSuccess = Expense::create([
        'amount'      => $request->get('amount'),
        'date'      => $request->get('date'),
        'name'      => $request->get('name'),
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
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
