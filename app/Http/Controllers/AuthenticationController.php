<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'username'=>'required',
            'password'=>'required',
        ]);

        if($validator->fails()):
            return $validator->errors();
        endif;

        $cred1 = ['username'=>$request->get('username'),'password'=>$request->get('password')];
        $cred2 = ['email'=>$request->get('username'),'password'=>$request->get('password')];

        if(auth()->attempt($cred1) or auth()->attempt($cred2)):
            return auth()->user()->createToken('api');
        endif;

        return response(400)->json(['message'=>'Invalid Credentials, Please Try Again.']);
    }

    public function registration(Request $request){
        $validator = Validator::make($request->all(),[
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'username'=>'required',
            'password'=>'required',
        ]);

        if($validator->fails()):
            return $validator->errors();
        endif;

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);

        if($user->save()):
            return $user->createToken('api');
        endif;

        return response(400)->json(['message'=>'Can\'t Register Now, Please Try Again Later.']);
    }
}
