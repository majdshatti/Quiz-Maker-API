<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\ResetCodePassword;
use App\Exceptions\ErrorResException;

class ForgotPasswordController extends Controller
{
    //
    

    //* @desc:Forget Password
    //* @route:/api/forgetPassword
    //* @access: PUBLIC
    
    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);
        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user
        $subject = "Reset Your password";

        $body = "We heard that you lost your password.Sorry about that!
                You Can use the following code to reset your password :";
        $code = (string) $data['code'];   
        $body =$body . $code;
        $isMailSent = sendMail($data['email'],$subject,$body);

        if (!$isMailSent) {
            throw new ErrorResException(
                getResMessage("serverError", [
                    "path" => "send verification code",
                ])
            );
        }
        return response(['message' => trans('passwords.sent')], 200);
    }

}
