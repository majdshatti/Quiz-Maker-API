<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Exceptions\ErrorResException;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;

class CodeCheckController extends Controller
{
    public function checkCode(Request $request)
    {
        $request->validate([
            "code" => "required|string|exists:reset_code_passwords",
            "password" => "required|string|min:6|confirmed",
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere("code", $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();

            throw new ErrorResException(
                getResMessage("resetCodeExpired", [
                    "path" => "Reset password code",
                ])
            );
        }

        // find user's email
        $user = User::firstWhere("email", $passwordReset->email);

        // update user password
        $user->update($request->only("password"));

        // delete current code
        $passwordReset->delete();

        return sendSuccRes(["keyMessage" => "resetSucc"], 200);
    }
}
