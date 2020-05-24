<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function postSignIn(Request $request) 
    {
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required'
        ]);
        $username = $request['username'];
        $password = $request['password'];
       if(Auth::attempt(['username' => $username, 'password' => $password]))
       {
           print("authenticated\n");
           return redirect()->route('dashboard');
       }
       return redirect()->back();
    }
    public function getDashboard()
    {
        return view('dashboard');
    }
}