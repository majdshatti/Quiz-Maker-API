<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Exceptions\ErrorResException;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request, $code)
    {
        $request->validate([
            "password" => "required|string|min:6|confirmed",
        ]);
        // find the code
        $passwordReset = ResetCodePassword::firstWhere("code", $code);

        if (!$passwordReset) {
            throw new ErrorResException("test", 404);
        }

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
        $user = User::where("email", $passwordReset->email)->first();
        echo $passwordReset->email;
        if (!$user) {
            throw new ErrorResException("not found user", 404);
        }

        // update user password
        $user->update(["password" => bcrypt($request->password)]);

        // delete current code
        ResetCodePassword::where("code", $code)->delete();

        return sendSuccRes(["keyMessage" => "resetSucc"], 200);
    }
}
