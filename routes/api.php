<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', function(Request $request){
    User::create([
        "name"=>"jm",
        "email"=>"jm@gmail.com",
        "password"=>bcrypt("jm")
        
    ]);
});

Route::post('login', function(Request $request){
    if(Auth::attempt($request->only('email', 'password'))){
        $user = Auth::user();
        $user= User::where('email', $request->email)->first();
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            // 'user' => $user,
            'token' => $token,
        ];

        $cookie = \cookie('sanctum', $token, 3600);

        return \response($response, 201)->withCookie($cookie);
    }
    return response ([
        'error' => 'Invalid Credentials',
    ], Response::HTTP_UNAUTHORIZED);

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
