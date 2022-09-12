<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ErrorResException;

class UserController extends Controller
{
    //* @desc:   Get a list of users
    //* @route:  GET /api/user
    //* @access: `ADMIN`
    public function getUsers(Request $request)
    {
        // Return a list of users
        return sendSuccRes([
            "data" => User::sort($request)
                ->filter(request(["name", "email", "rank"]))
                ->simplePaginate($request->query("limit") ?? 10),
        ]);
    }

    //* @desc:   Get a user by slug
    //* @route:  GET /api/user/{slug}
    //* @access: `ADMIN`
    public function getUserBySlug(Request $request, string $lang, string $slug)
    {
        $user = User::where("slug", $slug)->first();

        if (!$user) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => $slug])
            );
        }

        return sendSuccRes([
            "data" => $user,
        ]);
    }

    //* @desc:   Get a user by slug
    //* @route:  GET /api/user/{slug}
    //* @access: `USER`
    public function getLoggedUser(Request $request)
    {
        $user = User::where("slug", auth()->user()?->slug)->first();

        if (!$user) {
            throw new ErrorResException(
                transResMessage("notExist", ["value" => "user"])
            );
        }

        return sendSuccRes([
            "data" => $user,
        ]);
    }

    //* @desc:Change password for the user
    //* @route:/user/{slug}/changepassword
    //* @access: PUBLIC
    public function changePassword(Request $request, $slug)
    {
        $fields = $request->validate([
            "oldpassword" => "required|string",
            "newpassword" => [
                "required",
                "min:8",
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                "confirmed",
            ],
        ]);

        //Search for the user by slug.
        $user = User::where("slug", $slug)->first();

        //Check if the user exist
        if (!$user) {
            throw new ErrorResException(
                getResMessage("notExist", ["value" => $slug]),
                404
            );
        }

        //Check the Password for the user if it is correct.
        if (!Hash::check($fields["oldpassword"], $user->password)) {
            //respons message for old password
            return getResMessage("notCorrect", "Password");
        }

        //Update the password
        User::where("slug", $slug)->update([
            "password" => bcrypt($fields["newpassword"]),
        ]);
        return getResMessage("edited", "Password");
    }

    //* @desc:Edit user information
    //* @route:/user/{slug}/edituser
    //* @access: PUBLIC
    public function edituser(Request $request, $slug)
    {
        $fields = $request->validate([
            "newname" => "required|string",
        ]);

        //Search for the user by slug.
        $user = User::where("slug", $slug)->first();

        //Check if the user exist
        if (!$user) {
            throw new ErrorResException(
                getResMessage("notExist", ["value" => $slug]),
                404
            );
        }

        User::where("slug", $slug)->update([
            "name" => $fields["newname"],
        ]);

        return getResMessage("edited", ["path" => "user"]);
    }

    //* @desc:Delete user
    //* @route:/user/{slug}/deleteuser
    //* @access: PUBLIC
    public function deleteUser(Request $request, $slug)
    {
        $user = User::where("slug", $slug)->first();

        if (!$user) {
            throw new ErrorResException(
                getResMessage("notExist", ["value" => $slug]),
                404
            );
        }

        return getResMessage("deleted", ["path" => "user"]);
    }
}
