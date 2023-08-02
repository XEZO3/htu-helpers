<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class authController extends Controller
{
    public function register(Request $req){
        $fields = $req->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed|min:7|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'major'=>'required',
            'id_number'=>'required|min:7|integer|unique:users',
            'password_confirmation' => 'required| min:7'
            
        ]);
        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);
        $token = JWTAuth::fromUser($user);
        event(new Registered($user));
        $response = [
            'returnCode'=>"200",
            'message'=>"",
            'results'=>[
                'name'=>$user['name'],
                'token'=>$token
            ]
        ];
        return response($response,201);
    }
    public function logout(Request $req){
        $req->user()->tokens()->delete();
        return response([
            'returnCode'=>"200",
            'message'=>"logout!",
            'results'=>[]
            ],200);
    }
    public function login(Request $req){
        $fields = $req->validate([
            'id_number'=>'required',
            'password'=>'required'
        ]);
        
        $user = User::where('id_number',$fields['id_number'])->first();
        if(!$user ||!Hash::check($fields['password'],$user['password'])){
            return response(
                [
            'returnCode'=>"401",
            'message'=>"username or password is incorrect",
            'results'=>[]
                ],401);
        }
    $token = JWTAuth::fromUser($user);
        $response = [
            'returnCode'=>"200",
            'message'=>"",
            'results'=>[
                'name'=>$user['name'],
                'token'=>$token
            ]
        ];
        return response($response,201);   
    }
}
