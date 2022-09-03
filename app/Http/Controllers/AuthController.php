<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate Body Request
            $body = $request->validate([
                "name" => "required|string",
                "email" => "required|string|unique:user,email|email",
                "password" => "required|string|min:6",
            ]);

            // Hash password
            $body["password"] = bcrypt($body["password"]);

            // Set a user Pin for the verfication process
            $body["remember_token"] = sprintf("%06d", mt_rand(1, 999999));

            // Slug name
            $body["slug"] = Str::slug($body["name"]);

            // Create user
            $user = User::create($body);

            // Send email to user
            sendMail(
                $user["email"],
                "Quiz Maker Account Activation",
                $user["remember_token"] . ""
            );

            //TODO: JWT TOKEN
            // Create a token
            $token = $user->createToken("myapptoken")->plainTextToken;
        } catch (Exception $err) {
            return response(
                ["success" => false, "message" => $err->getMessage()],
                400
            );
        }

        // Return success response
        return response(
            [
                "success" => true,
                "data" => $user,
                "token" => $token,
            ],
            201
        );
    }
}
