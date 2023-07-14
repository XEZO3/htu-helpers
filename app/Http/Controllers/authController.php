<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
class authController extends Controller
{
    public function register(Request $req){
        $fields = $req->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed|min:7|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'major'=>'required',
            'id_number'=>'required|min:8|integer',
            'password_confirmation' => 'required| min:7'
            
        ]);
        $fields['password'] = bcrypt($fields['password']);
        $user = User::create($fields);
        $token = $user->createToken('myapptoken')->plainTextToken;
        event(new Registered($user));
        $response = [
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,201);
    }
    public function logout(Request $req){
        $req->user()->tokens()->delete();
        return [
            'message'=>"logout"
        ];
    }
    public function login(Request $req){
        $fields = $req->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        
        $user = User::where('email',$fields['email'])->first();
        if(!$user ||!Hash::check($fields['password'],$user['password'])){
            return response(['message'=>"username or password is incorrect"],401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,201);   
    }
}
