<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
//use App\Events\SendMailOnRegisterUser;
use App\User;
use Auth;
use Mail;

class UserRegistretionController extends Controller
{
    /**
     * Create a new user.
     *
     * @return void
     */
    public function userRegistration(Request $request) {
        
        $ruls = [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:8|confirmed',
                ];

        $message = [
                        'name.required' => 'Please Enter Name.',
                        'email.required' => 'Please Enter Your Email Address.',
                        'email.email' => 'Please Enter Valid Email Format.',
                        'email.unique' => 'Sorry, This Email Address Is Already Used By Another User. Please Try With Different One.',
                        'password.required' => 'Please enter password.',
                        'password_confirmation' => 'The password confirmation does not match.'
                    ];
        $this->validate($request,$ruls,$message);
        $user_details = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'user_type' => $request['user_type'],
                    'password' => bcrypt($request['password']),
                    'remember_token' => $request['_token'],
                ]);
        if($user_details){
            Auth::login($user_details);
            if($request['user_type'] == "Admin"){
                //$user = User::find($event->userId)->toArray();
                Mail::send('emails.mailEvent', $user_details, function($message) use ($user_details) {
                    $message->to("jack@yopmail.com");
                    $message->subject('Event Testing');
                });
                //event(new SendMailOnRegisterUser(1));
                $request->session()->flash('success-message','Registration successfully as admin.');
                return redirect('/en/admin/dashboard');
            }elseif($request['user_type'] == "User"){
                //event(new SendMailOnRegisterUser(1));
                $request->session()->flash('success-message','Registration successfully as user.');
                return redirect('/en/user/dashboard');
            }else{
                $request->session()->flash('error-message','Something Wrong in create new user.Please try again.');
                return redirect()->back();
            }
        }else{
            $request->session()->flash('error-message','Something Wrong in create new user.Please try again.');
            return redirect()->back();
        }
    }
}
