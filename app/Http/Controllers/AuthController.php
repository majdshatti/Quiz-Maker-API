<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //* @desc: Register a user
    //* @route: /api/register
    //* @access: PUBLIC
    public function register(Request $request)
    {
        // Validate Body Request
        $body = $request->validate([
            "name" => "required|string",
            "email" => "required|string|unique:user,email|email",
            "password" => "required|string|min:6",
        ]);

        // Hash password
        $body["password"] = bcrypt($body["password"]);

        // Set a pin number for verfication process and slugify name
        $default = [
            "remember_token" => sprintf("%06d", mt_rand(1, 999999)),
            "slug" => Str::slug($body["name"]),
        ];

        // Create user
        $user = User::create(array_merge($body, $default));

        // Send email to user
        sendMail(
            $user["email"],
            "Quiz Maker Account Activation",
            $user["remember_token"]
        );

        //TODO: JWT TOKEN
        // Create a token
        $token = $user->createToken("myapptoken")->plainTextToken;

        // Incase function returned a falsy value
        $resMessage = getResMessage("registered")
            ? getResMessage("registered")
            : "";

        // Return success response
        return response(
            [
                "success" => true,
                "message" => $resMessage,
                "data" => $user,
                "token" => $token,
            ],
            201
        );
    }
}
