<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function proses(Request $request){
        $request->validate([
            'email'=> 'required',
            'password'=> 'required',
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(Auth::attempt($data)){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('login')->with('failed','Email dan Passwaord tidak sesuai');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('succes', 'Anda memilih logout');
    }

    public function register(){
        return view('register');
    }

    public function sign(Request $request){
       $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
       ]);

       $data['name'] = $request->name;
       $data['email'] = $request->email;
       $data['password'] = Hash::make($request->password);

       User::create($data);

       return redirect()->route('login')->with('succes', 'Anda berhasil register');

    }
}