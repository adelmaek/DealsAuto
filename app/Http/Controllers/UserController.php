<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\currency;
use Log;

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
           return redirect()->route('home');
       }
       return redirect()->back();
    }
    public function getHome()
    {
        $currencies = currency::all();
        return view('home',["currencies"=>$currencies]);
    }

    public function postHome(Request $request)
    {
        for ($i = 0; $i<count($request['nameInput']); $i++)  
        {   
            if(!empty(currency::where('name', $request['nameInput'][$i])->first()))
                currency::update_currency_rate($request['nameInput'][$i], $request['rateInput'][$i]);
            else
                currency::insert_new_currency($request['nameInput'][$i], $request['rateInput'][$i]);   
        }
        return redirect()->back();
    }


    public function postLogout(Request $request)
    {
        Auth::logout();
        return redirect() ->route('welcome');
    }
}