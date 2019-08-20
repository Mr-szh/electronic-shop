<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\VarDumper\VarDumper;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        return view('user_information.index', [
            'user' => $user,
        ]);
    }

    public function update(UserRequest $request)
    {
        $name = $request->input('name');
        $birthday = $request->input('birthday');
        $sex = $request->input('sex');

        Auth::user()->name = $name;
        Auth::user()->birthday = $birthday;
        Auth::user()->sex = $sex;
        // var_dump($sex);

        Auth::user()->save();

        return [];
    }

    public function change()
    {
        return view('user_information.change_password', ['user' => Auth::user()]);
    }

    public function replace(PasswordRequest $request)
    {
        $password = $request->input('password');
        $password = Hash::make($password);

        Auth::user()->password = $password;
        Auth::user()->save();
        
        return []; 
    }
}
