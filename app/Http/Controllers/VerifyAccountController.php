<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResException;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyAccountController extends Controller
{
    //* @desc: Verify a user account
    //* @route: /api/verify/{token}
    //* @access: PUBLIC
    public function verifyAccount($token)
    {
        $user = User::where("remember_token", "=", $token)->first();

        // Check user with the provided Pin token
        if (!$user) {
            throw new ErrorResException("invalidToken", 404);
        }

        // Check if account is already verfied
        if (!is_null($user->email_verified_at)) {
            throw new ErrorResException("alreadyVerfied", 404);
        }

        // Update verification date
        $user->update([
            "email_verified_at" => date_default_timezone_get(),
            "remember_token" => null,
        ]);

        return sendSuccRes(
            [
                "keyMessage" => "verified",
            ],
            200
        );
    }
}
