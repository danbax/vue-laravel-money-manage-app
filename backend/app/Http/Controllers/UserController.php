<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * get user data 
     * input: token, email
     * @return user object
     */
    public static function getUser(Request $request){
        $user = User::find($request->get("clientId"));
        if($user->remember_token == $request->get("token")){
            return $user;
        }
        return false;
    }

    /**
     * checks if the user's token is valid
     */
    public static function isValidToken(Request $request){
        $user = User::find($request->get("clientId"));
        if($user->remember_token == $request->get("token")){
            return true;
        }
        return false;
    }

    /*
        replaces the token in the database
    */
    public function logout(Request $request){
        $user = $this->getUser($request);
        
        $user = Auth::user(); 
        $token = Str::random(64);
        $user->remember_token = $token;
        $user->save();
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $message = "פרטים לא תקינים";
            return ApiController::errorResponse($message);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); 
            $token = Str::random(64);
            $user->remember_token = $token;
            $user->save();

            $minutes = 60*24*365;
            Cookie::make('token', $token, $minutes);
            Cookie::make('clientId', $user->id, $minutes);

            $data = ['token'=>$token,'clientId'=>$user->id,'isFirstTime'=>$user->isFirstTime];
            return ApiController::successResponse($data);
        }

        $message = "קרתה תקלה";
        return ApiController::errorResponse($message);
    }

    public function user(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'clientId' => 'required'
        ]);

        if ($validator->fails()) {
            $message = "פרטים לא תקינים";
            return ApiController::errorResponse($message);
        }

        try{
            $user = User::find($request->get("clientId"));
            if($user->remember_token == $request->get("token")){        
                $data = [
                    'token'=>$user->remember_token,
                    'clientId'=>$user->id,
                    'isFirstTime'=>$user->isFirstTime == 1 ? true : false,
                    'currentAmount' => $user->current_amount,
                    'incomeAmount' => $user->income_amount,
                    'incomeDate' => $user->income_date
                ];
                return ApiController::successResponse($data);
            }
        }catch(Exception $ex){
            $message = $ex->getMessage();
            return ApiController::errorResponse($message);
        }

        $message = "קרתה תקלה";
        return ApiController::errorResponse($message);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    
    protected function rules(array $data)
    {
        return [
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users'
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|email',
        ]);

        if ($validator->fails()) {
            $message = "אימייל לא תקין או קיים כבר במערכת";
            return ApiController::errorResponse($message);
        }

        $isSuccess = User::create([
        'email'     => $request->get('email'),
        'password'  => bcrypt($request->get('password')),
        'isFirstTime' => true,
        'default_screen' => $request->get("defaultScreen")
        ]);
        

        if($isSuccess){
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user(); 
                $token = Str::random(64);
                $user->remember_token = $token;
                $user->save();
    
                $minutes = 60*24*365;
                Cookie::make('token', $token, $minutes);
                Cookie::make('clientId', $user->id, $minutes);
    
                $data = ['token'=>$token,'clientId'=>$user->id,'isFirstTime'=>$user->isFirstTime];
                return ApiController::successResponse($data);
            }
    
            $message = "קרתה תקלה";
            return ApiController::errorResponse($message);
        }else{
            $message = "תקלה בבסיס הנתונים";
        }

        return ApiController::errorResponse($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'clientId'     => 'required',
            'token'  => 'required'
        ]);

        if(!$this->isValidToken($request)){
            $message = "טוקן לא תקין";
            return ApiController::errorResponse($message);
        }
        $user = $this->getUser($request);

        if ($request->has('currentAmount') && $request->get('currentAmount')) {
            $user->current_amount = $request->get('currentAmount');
        }
        if ($request->has('incomeAmount') && $request->get('incomeAmount')) {
            $user->income_amount = $request->get('incomeAmount');
        }
        if ($request->has('incomeDate') && $request->get('incomeDate')) {
            $user->income_date = $request->get('incomeDate');
        }
        $user->isFirstTime = false;
        $isSuccess = $user->save();

        if($isSuccess){
            $code = 200;
            $user->token = $user->remember_token;
            return ApiController::successResponse($user);
        }else{
            $code=200;
            $message = "תקלה בבסיס הנתונים";
        }

        return ApiController::errorResponse($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
