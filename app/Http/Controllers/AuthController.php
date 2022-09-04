<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorResException;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
                "keyMessage" => "registered",
            ],
            201
        );
    }
}
