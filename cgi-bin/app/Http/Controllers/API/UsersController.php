<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function updateProfile(Request $request){
        $user = Users::find($request->id);
        $user->name = $request->name;
        $user->mobile = $request->contact;

        $destination_path = public_path('media');
        if($request->file('profile_image'))
        {
            $file = $request->file('profile_image');
            $name = $file->getClientOriginalName();
            $file->move($destination_path, $name);
            $user->profile_image = $name;
        }

        $user->save();
        return $user;
    }

    public function updatePassword(Request $request){
        $user = Users::find($request->id);
        if(Hash::check($request->old_password, $user->password))
        {
            if($request->new_password == $request->confirm_password){
                $user->password = Hash::make($request->new_password);
                $user->save();
            }
            else
            {
                return response()->json('New Password and Confirm new password fields does not match.', 422); 
            }
        }
        else
        {
            return response()->json('Old password is incorrect, Please check again', 422); 
        }
    }
}
