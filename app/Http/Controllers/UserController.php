<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //
    public function getUsers(){

    }

    //* @desc:Login user using email and password
    //* @route:/api/login
    //* @access: PUBLIC
    public function login(Request $request)
    {
        $fields = $request->validate([
           'email'=>'required|string',
           'password'=>'required|string' 
        ]);

        $user = User::where('email',$fields['email'])->first();

        //Check the Email for the user if it is not correct.
        if(!$user)
        {
            return response([
                'message'=>'incorrect email'
            ],401);
        }

        //Check the Password for the user if it is not correct.
        if(!Hash::check($fields['password'],$user->password))
        {
            return response([
                'message'=>'incorrect password'
            ],401);
        }

        //return response for the user if everything was correct.
        $token = $user->createToken('usertoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    //Logout
    //* @desc:Logout
    //* @route:/api/logout
    //* @access: PUBLIC
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        
       /* return sendSuccRes(
            [
                "data" => $user,
                "keyMessage" => "registered",
            ],
            201
        );
    */
    }

    //* @desc:Change password for the user
    //* @route:/api/changePassword
    //* @access: PUBLIC
    public function changePassword(Request $request)
    {

        $fields = $request->validate([
            'email'=>'required|string',
            'oldpassword' =>'required|string',
            'newpassword'=> ['required', 
            'min:8', 
            'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'confirmed'] ,
         ]);
         //Search for the user by email.
        $user = User::where('email',$fields['email'])->first();
        
        //Check the Email for the user if it is correct.
        if(!$user)
        {
            return response([
                'message'=>'incorrect email'
            ],401);
        }

        //Check the Password for the user if it is correct.
        if(!Hash::check($fields['oldpassword'],$user->password))
        {
            return response([
                'message'=>'incorrect password'
            ],401);
        }

        //Update the password
        User::where('email', $fields['email'])
       ->update([
           'password' => bcrypt($fields['newpassword'])
        ]);
        return [
            'message' => 'Password changed'
        ];
    }

    //* @desc:Forget Password
    //* @route:/api/forgetPassword
    //* @access: PUBLIC
    
    public function forgetPassword(Request $request)
    {
        $fields = $request->validate([
            'email'=>'required|string',
         ]);

         $user = User::where('email',$fields['email'])->first();
         
        if(!$user)
        {
            return response([
                'message'=>'incorrect email'
            ],401);
        }
        
        $token = bcrypt($user->createToken('usertoken')->plainTextToken);
        
        $subject = "Reset Your password";

        $body = "We heard that you lost your password.Sorry about that!
                You Can use the following link to reset your password :";
        //edit the link
        $link = "auth/reset-password/" + $token;

        $body += $link;
        sendMail($fields['email'],$subject,$body);

        return response([
            'message'=>'Success'
        ],200);
    }
}
