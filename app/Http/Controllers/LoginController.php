<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){
		if(Auth::attempt(['email'=> $request->email , 'password'=>$request->password])){
			$user = User::where('email',$request->email)->first();
			if(isset($user->user_type) && $user->user_type != "" && $user->user_type == "Admin"){

				return redirect('/admin/dashboard');

			}else if(isset($user->user_type) && $user->user_type != "" && $user->user_type == "User"){

				return redirect('/user/dashboard');

			}else{
				$request->session()->flash('error_message', "Credential dos't match with our records");
    			return redirect()->back()->with('error',"credential dos't match in our records");
			}
		}

		$request->session()->flash('error_message', "Credential dos't match with our records");
		
		return redirect()->back();
    }
}
