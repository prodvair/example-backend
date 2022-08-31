<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\http\Resources\UserResource;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.api');
    }

    public function update(UpdateUserRequest $request)
    {
        $user = User::find(auth()->user()->id);

        if (isset($request->first_name)) {
            $user->first_name   = $request->first_name;
        }
        if (isset($request->last_name)) {
            $user->last_name    = $request->last_name;
        }
        if (isset($request->email)) {
            $user->email        = $request->email;
        }
        if (isset($request->birthday)) {
            $user->birthday     = $request->birthday;
        }

        $user->save();

        return new UserResource($user);
    }

    public function updatePassword(UpdateUserPasswordRequest $request)
    {
        $user = User::find(auth()->user()->id);

        $user->newPassword($request->new_password);

        return new UserResource($user);
    }
}
