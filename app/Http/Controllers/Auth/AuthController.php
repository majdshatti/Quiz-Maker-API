<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Exceptions\ErrorResException;
use App\Http\Requests\User\UserStoreRequest;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //* @desc:   Register a user
    //* @route:  POST /api/register
    //* @access: PUBLIC
    public function register(UserStoreRequest $request)
    {
        // Get validated Body Request
        $body = $request->validated();

        // Hash password
        $body["password"] = bcrypt($body["password"]);

        // Set a pin number for verfication process and slugify name
        $default = [
            "remember_token" => sprintf("%06d", mt_rand(1, 999999)),
            "slug" => Str::slug($body["name"]),
            "uuid" => Str::orderedUuid()->getHex(),
        ];

        // Send email to user
        $isMailSent = sendMail(
            $body["email"],
            "Quiz Maker Account Activation",
            $default["remember_token"]
        );

        if (!$isMailSent) {
            throw new ErrorResException(
                getResMessage("serverError", [
                    "path" => "send verification code",
                ])
            );
        }

        // Create user
        $user = User::create(array_merge($body, $default));

        // Return success response
        return sendSuccRes(
            [
                "data" => $user,
                "message" => transResMessage("created", ["path" => "user"]),
            ],
            201
        );
    }

    //* @desc:Login user using email and password
    //* @route:/api/login
    //* @access: PUBLIC
    public function login(Request $request)
    {
        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required|string",
        ]);

        $user = User::where("email", $fields["email"])->first();

        //Check the Email for the user if it is not correct.
        if (!$user) {
            throw new ErrorResException(getResMessage("creds"), 400);
        }

        //Check the Password for the user if it is not correct.
        if (!Hash::check($fields["password"], $user->password)) {
            throw new ErrorResException(getResMessage("creds"), 400);
        }

        //return response for the user if everything was correct.
        $token = $user->createToken("usertoken")->plainTextToken;
        return sendSuccRes(
            [
                "user" => $user,
                "token" => $token,
            ],
            201
        );
    }

    //Logout
    //* @desc:Logout
    //* @route:/api/logout
    //* @access: PUBLIC
    public function logout(Request $request)
    {
        $request
            ->user()
            ->tokens()
            ->delete();

        return sendSuccRes(
            [
                "keyMessage" => "logout",
            ],
            201
        );
    }
}
