<?php

namespace App\Http\Controllers\Admin;

use Stevebauman\Translation\Contracts\Translation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Helpers\Helper;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Translation $translation) {
        $this->middleware('auth');
        $this->translation = $translation;
    }

    /*  
    * Admin Home 
    *
    */
    public function AdminHome(){
    	$user_details = User::Find(1)->phone()->get();

    	/*$api_key = "5d222cf9a8b2cd99e493ea660ba5a548-us18"; // YOUR API KEY
		$server = 'us18';

    	$mode = "Delete";
    	// Get List Or create new member in that list
    	if($mode == "List"){
    		$url = 'https://'.$server.'.api.mailchimp.com/3.0/lists/';
			$camp_url = 'https://'.$server.'api.mailchimp.com/3.0/campaigns/';
			$auth = base64_encode( 'user:'.$api_key );
			$send_data = array('fields' => 'lists');
			// Get All List
			$mailchimp_lists = $this->requerstInMailChimp($send_data, $api_key,$url,"GET");
			echo "<pre>"; print_r($mailchimp_lists);
    	}else if($mode == "New"){
    		$list_id = "e881353d9b"; // List Id
			// Create new member in above list
			$data_for_list = array(
			'apikey'        => $api_key,
			'email_address' => "shani.iihglobal@gmail.com",
			'status'        => 'subscribed',
			'merge_fields'  => array(
			    'FNAME' => "",
			    'LNAME' => ""
			    )
			);
			$url = 'https://'.$server.'.api.mailchimp.com/3.0/lists/'.$list_id.'/members/';
			
			$api_response = $this->requerstInMailChimp($data_for_list, $api_key, $url,"POST");
			echo "<pre>"; print_r($api_response); exit;
    	}else if($mode == "Delete"){
    		// Delete Member from list
			try{
				$list_id = "e881353d9b"; // List Id
				$member_id = md5(strtolower("shani.iihglobal@gmail.com")); // Member Id
				$url = 'https://'.$server.'.api.mailchimp.com/3.0/lists/'.$list_id.'/members/'.$member_id;
				$data = array();
				$return = $this->requerstInMailChimp($data,$api_key,$url,"Delete");
				echo "<pre>"; print_r($return);
			} catch (\Mailchimp_Error $e) {
				echo  "Error from MailChimp"; exit;
			}
    	}else{
    		echo 'No Action';
    	}*/
    	return view('admin/admin_dashboard');
    }

    public function requerstInMailChimp(array $data, $apikey,$url,$method = ""){
       	$auth          = base64_encode('user:' . $apikey);
        $json_postData = json_encode($data);
        $ch            = curl_init();
        $curlopt_url   = $url;
        curl_setopt($ch, CURLOPT_URL, $curlopt_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Basic ' . $auth));
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
        if(isset($method) && $method != ""){
        	if($method == "GET"){
	        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        	$httpCode = curl_getinfo($ch);
	        	$result = curl_exec($ch);
	        	return json_decode($result);
	        }else if($method == "POST"){
	        	curl_setopt($ch, CURLOPT_POST, true);
	        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_postData);
		        $result = curl_exec($ch);
		        return json_decode($result);
	        }else if($method == "Delete"){
	        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	            $result = curl_exec($ch);
	            return json_decode($result);
	        }
        }else{
        	return "Bad method request";
        }
    }
   
    public function editUserProfile(Request $request, $userId){
		$user_details = User::Find($userId)->toArray();
		$response_data['user_details'] = $user_details;

		return view('admin/edit_admin_profile',$response_data);
    }

   	public function UpdateAdminProfile(Request $request){
   		//echo "<pre>"; print_r($request->all()); exit;
   		$ruls = [
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
        $this->validate($request,$ruls,$message);

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
        $request->session()->flash('success-message','Admin profile details updated successfully.');
        return redirect()->back();
   	}
}
