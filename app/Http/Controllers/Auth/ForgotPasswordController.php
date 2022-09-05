<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ForgotPassowrd extends Controller
{
    //
    

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
