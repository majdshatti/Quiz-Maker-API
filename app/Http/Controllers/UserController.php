<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ErrorResException;

class UserController extends Controller
{
    //
    public function getUsers(){

    }
    //* @desc:Change password for the user
    //* @route:/user/{slug}/changepassword
    //* @access: PUBLIC
    public function changePassword(Request $request ,$slug)
    {

        $fields = $request->validate([
            'oldpassword' =>'required|string',
            'newpassword'=> ['required', 
            'min:8', 
            'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'confirmed'] ,
         ]);
        
        //Search for the user by slug.
        $user = User::where('slug',$slug)->first();
        
        //Check if the user exist
        if(!$user)
        {
            throw new ErrorResException(getResMessage("notExist", ["value" => $slug]), 404);
        }

        //Check the Password for the user if it is correct.
        if(!Hash::check($fields['oldpassword'],$user->password))
        {
            //respons message for old password
            return getResMessage("notCorrect","Password");
        }

        //Update the password
        User::where('slug',$slug)
       ->update([
           'password' => bcrypt($fields['newpassword'])
        ]);
        return getResMessage("edited","Password");
    }

    //* @desc:Edit user information
    //* @route:/user/{slug}/edituser
    //* @access: PUBLIC
    public function edituser(Request $request,$slug)
    {
        $fields = $request->validate([
            "newname" =>"required|string"
         ]);
         
        //Search for the user by slug.
        $user = User::where('slug',$slug)->first();
        
        //Check if the user exist
        if(!$user)
        {
            throw new ErrorResException(getResMessage("notExist", ["value" => $slug]), 404);
        }

        User::where('slug',$slug)
        ->update([
            'name' => $fields['newname']
         ]);

        return getResMessage("edited","Name");
    }

    //* @desc:Delete user
    //* @route:/user/{slug}/deleteuser
    //* @access: PUBLIC
    public function deleteUser(Request $request,$slug)
    {
        $user = User::where('slug',$slug)->first();

        if(!$user)
        {
            throw new ErrorResException(getResMessage("notExist", ["value" => $slug]), 404);
        }
        
        User::where('slug',$slug)
        ->delete();

        return getResMessage("deleted","User");
    }
}
