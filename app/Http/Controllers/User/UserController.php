<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Helpers\Helper;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /*  
    * User Home 
    *
    */
    public function UserHome(){

    	return view('user/user_dashboard');
    	
    }

    public function editUserProfile(Request $request, $userId){

        $user_details = User::Find($userId)->toArray();
        $response_data['user_details'] = $user_details;

        return view('user/edit_user_profile',$response_data);
    }

    public function UpdateAdminProfile(Request $request){
        
        $rules = [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,id,'.$request['user_id'],
                    'address' => 'required',
                ];

        $message = [
                        'name.required' => 'Please Enter Name.',
                        'email.required' => 'Please Enter Your Email Address.',
                        'email.email' => 'Please Enter Valid Email Format.',
                        'email.unique' => 'Sorry, This Email Address Is Already Used By Another User. Please Try With Different One.',
                        'address.required' => 'Please enter location.',
                    ];
        $this->validate($request,$rules,$message);
        
        $latLong = Helper::getLatLong($request['address']);
        if(isset($latLong['results'][0])){
            $latitude = $latLong['results'][0]['geometry']['location']['lat'];
            $longitude = $latLong['results'][0]['geometry']['location']['lng'];
        }else{
            $latitude = NULL;
            $longitude = NULL;
        }

        $user_details = User::where('id',$request['user_id'])->update([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'address' => $request['address'],
                    'lat' => $latitude,
                    'lng' => $longitude
                ]);

        $request->session()->flash('success-message','User profile details updated successfully.');
        return redirect()->back();
    }
}