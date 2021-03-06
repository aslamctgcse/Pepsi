<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\Traits\SendSms;
//use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use PaytmWallet;
use Mail;
//use App\Paytm;    

class UserController extends Controller
{
    use SendSms;
    
    public function signUp(Request $request)
    {   
        $this->validate(
            $request, 
            [
                'user_name' => 'required',
                'user_email' => 'required|email',
                'user_phone' => 'required',
                'user_password' => 'required'
            ],
            [
                'user_name.required' => 'Enter Name...',
                'user_email.required' => 'Enter email...',
                'user_phone.required' => 'Enter Mobile...',
                'user_password.required' => 'Enter password...',
            ]
        );
    	$user_name  = $request->user_name;
    	$user_email = $request->user_email;
    	$user_phone = $request->user_phone;
    	$user_image = $request->user_image;
    	$user_password = $request->user_password;
    	$device_id  = $request->device_id;
        $created_at = Carbon::now();
        $updated_at = Carbon::now();

        // $date=date('d-m-Y');
    	$checkUser = DB::table('users')
    					->where('user_phone', $user_phone)
    					->first();
     
        $smsby = DB::table('smsby')
                    ->first();

        if($smsby->status==1) {
           
            // check for otp verify
        	if($checkUser && $checkUser->is_verified == 1) {
        		$message = array('status'=>'0', 'message'=>'user already registered', 'data'=>[]);
                return $message;
        	} else if($checkUser && $checkUser->is_verified==0) { ///////if phone not verified/////
                
                $delnot= DB::table('notificationby')
    					    ->where('user_id', $checkUser->user_id)
    			     	    ->delete();
        						
    	    	$delUser = DB::table('users')
        					->where('user_phone', $user_phone)
        					->delete();
        	    
        	    if($request->user_image) {
                    $user_image = $request->user_image;
                    $user_image = str_replace('data:image/png;base64,', '', $user_image);
                    $fileName = str_replace(" ", "-", $user_image);
                    $fileName = date('dmyHis').'user_image'.'.'.'png';
                    $fileName = str_replace(" ", "-", $fileName);
                    \File::put(public_path(). '/images/user/' . $fileName, base64_decode($user_image));
                    $user_image = 'images/user/'.$fileName;
                } else {
                    $user_image = 'N/A';
                }
            
        		$insertUser = DB::table('users')
        						->insertGetId([
        							'user_name'  =>     $user_name,
        							'user_email' =>     $user_email,
        							'user_phone' =>     $user_phone,
        							'user_image' =>     $user_image,
        							'user_password' =>  $user_password,
        							'device_id'     =>  $device_id,
        							'reg_date'      =>  $created_at
        						]);
        						
            	$Userdetails = DB::table('users')
            					->where('user_phone', $user_phone)
            					->first();
                					
        		if($insertUser) {
        		     DB::table('notificationby')
        						->insert(['user_id'=> $insertUser,
        						'sms'=> '1',
        						'app'=> '1',
        						'email'=> '1']);
        						
        						
        			$chars = "0123456789";
                    $otpval = "";
                    for ($i = 0; $i < 4; $i++){
                        $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                    }
                    
                    
                    $otpmsg = $this->otpmsg($otpval,$user_phone);
                    
                    $updateOtp = DB::table('users')
                                    ->where('user_phone', $user_phone)
                                    ->update(['otp_value'=>$otpval]);
        						
    	    		$message = array('status'=>'1', 'message'=>'OTP Sent', 'data'=>$Userdetails);
    	        	return $message;
    	    	} else {
    	    	    
    	    		$message = array('status'=>'0', 'message'=>'Something went wrong');
    	            return $message;
    	    	}  
        	} else { ///////new user/////
        	
                if($request->user_image) {
                    $user_image = $request->user_image;
                    $user_image = str_replace('data:image/png;base64,', '', $user_image);
                    $fileName = str_replace(" ", "-", $user_image);
                    $fileName = date('dmyHis').'user_image'.'.'.'png';
                    $fileName = str_replace(" ", "-", $fileName);
                    \File::put(public_path(). '/images/user/' . $fileName, base64_decode($user_image));
                    $user_image = 'images/user/'.$fileName;
                } else {
                    $user_image = 'N/A';
                }
            
        		$insertUser = DB::table('users')
        						->insertGetId([
        							'user_name'     =>  $user_name,
        							'user_email'    =>  $user_email,
        							'user_phone'    =>  $user_phone,
        							'user_image'    =>  $user_image,
        							'user_password' =>  $user_password,
        							'device_id'     =>  $device_id,
        							'reg_date'      =>  $created_at
        						]);
        						
            	$Userdetails = DB::table('users')
            					->where('user_phone', $user_phone)
            					->first();
            					
        		if($insertUser) {
        		    
        		     DB::table('notificationby')
        						->insert([
                						    'user_id'   => $insertUser,
                						    'sms'       => '1',
                						    'app'       => '1',
                						    'email'     => '1'
        						        ]);
        						
        			$chars  = "0123456789";
                    $otpval = "";
                    for ($i = 0; $i < 4; $i++){
                        $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                    }
                    
                    $otpmsg = $this->otpmsg($otpval,$user_phone);
        
                    $updateOtp = DB::table('users')
                                    ->where('user_phone', $user_phone)
                                    ->update(['otp_value' => $otpval]);
        						
    	    		$message = array('status' => '1', 'message' => 'OTP Sent', 'data' => $Userdetails);
    	    		
    	        	return $message;
    	    	} else {
    	    		$message = array('status' => '0', 'message' => 'Something went wrong');
    	            return $message;
    	    	}
        	}
        } else {

            if($checkUser AND $checkUser->is_verified == 0) {
                
                DB::table('notificationby')
						->insert([
        						    'user_id'   => $checkUser->user_id,
        						    'sms'       => '1',
        						    'app'       => '1',
        						    'email'     => '1'
						        ]);
    						
    			$chars  = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                
                $otpmsg = $this->otpmsg($otpval, $user_phone);
    
                $updateOtp = DB::table('users')
                                ->where('user_phone', $user_phone)
                                ->update(['otp_value' => $otpval]);
                                
	    		$message = array('status' => '1', 'message' => 'OTP Sent', 'data' => $checkUser);
	    		
	        	return $message;
            } else if ($checkUser AND $checkUser->is_verified == 1) {
                
        		$message = array('status'=>'0', 'message'=>'user already registered',  'data' => $Userdetails);
                return $message;
                
        	} else {
        	    
        	    if($request->user_image){
                    $user_image = $request->user_image;
                    $user_image = str_replace('data:image/png;base64,', '', $user_image);
                    $fileName = str_replace(" ", "-", $user_image);
                    $fileName = date('dmyHis').'user_image'.'.'.'png';
                    $fileName = str_replace(" ", "-", $fileName);
                    \File::put(public_path(). '/images/user/' . $fileName, base64_decode($user_image));
                    $user_image = 'images/user/'.$fileName;
                } else {
                    $user_image = 'N/A';
                }
        
        		$insertUser = DB::table('users')
        						->insertGetId([
        							'user_name'  => $user_name,
        							'user_email' => $user_email,
        							'user_phone' => $user_phone,
        							'user_image' => $user_image,
        							'user_password' => $user_password,
        							'device_id' => $device_id,
        							'reg_date'  => $created_at,
        				// 			'is_verified' => 1,
                                    'otp_value'   => NULL
        						]);
    						
            	$Userdetails = DB::table('users')
            					->where('user_phone', $user_phone)
            					->first();
            					
    		    if($insertUser) {
    		        
        		     DB::table('notificationby')
        						->insert([
                						    'user_id'   => $insertUser,
                						    'sms'       => '1',
                						    'app'       => '1',
                						    'email'     => '1'
        						        ]);
        						
        			$chars  = "0123456789";
                    $otpval = "";
                    for ($i = 0; $i < 4; $i++){
                        $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                    }
                    
                    $otpmsg = $this->otpmsg($otpval, $user_phone);
        
                    $updateOtp = DB::table('users')
                                    ->where('user_phone', $user_phone)
                                    ->update(['otp_value' => $otpval]);
        						
    	    		$message = array('status' => '1', 'message' => 'OTP Sent', 'data' => $Userdetails);
    	    		
    	        	return $message;
    	        	
    					
        // 			$message = array('status' => '2', 'message' => 'Login Successfully', 'data' => $Userdetails);
        //             return $message;			
                }
            }
        }
    }
    
    public function verifyPhoneOTP(Request $request)
    {
        $phone  = $request->user_phone;
        $otp    = $request->otp;

        // check for otp verify
        $getUser    =   DB::table('users')
                            ->where('user_phone', $phone)
                            ->first();
                    
        if($getUser AND $getUser->is_verified == 0) {
            $getotp = $getUser->otp_value;
            
            if($otp == $getotp){
                // verify phone
                $getUser = DB::table('users')
                            ->where('user_phone', $phone)
                            ->update([
                                'is_verified' => 1,
                                'otp_value'   => NULL]);
                    
                $message = array('status'=>1, 'message'=>"Phone Verified! login successfully");
                return $message;
            } else {
                $message = array('status'=>0, 'message'=>"Wrong OTP");
                return $message;
            }
        } else if ($getUser AND $getUser->is_verified == 1) {
                
            $message = array('status'=>1, 'message'=>"Phone Verified! login successfully");
            return $message;
        } else {
            $message = array('status'=>0, 'message'=>"User not registered");
            return $message;
        }
    }
    
    public function verifyPhone(Request $request)
    {
        $phone = $request->user_phone;
        $otp    = $request->otp;
        $smsby = DB::table('smsby')
                    ->first();
                    
        if($smsby->status == 1) {      
            // check for otp verify
            $getUser = DB::table('users')
                        ->where('user_phone', $phone)
                        ->first();
                    
            if($getUser){
                $getotp = $getUser->otp_value;
                
                if($otp == $getotp){
                    // verify phone
                    $getUser = DB::table('users')
                                ->where('user_phone', $phone)
                                ->update([
                                    'is_verified' => 1,
                                    'otp_value'   => NULL]);
                        
                    $message = array('status'=>1, 'message'=>"Phone Verified! login successfully");
                    return $message;
                } else {
                    $message = array('status'=>0, 'message'=>"Wrong OTP");
                    return $message;
                }
           
            } else {
                $message = array('status'=>0, 'message'=>"User not registered");
                return $message;
            }
        } else {
              $getUser = DB::table('users')
                            ->where('user_phone', $phone)
                            ->update(['is_verified'=>1,
                            'otp_value'=>NULL]);
             $message = array('status'=>1, 'message'=>"Phone Verified! login successfully");
            return $message;
        }
    }


    public function login(Request $request)
    
     {
    	$user_phone = $request->user_phone;
    	$user_password = $request->user_password;
    	$device_id = $request->device_id;
    	
    	$checkUserReg = DB::table('users')
    					->where('user_phone', $user_phone)
    					->first();
    					
    	if(!($checkUserReg) || $checkUserReg->is_verified== 0){
    	    $message = array('status'=>'0', 'message'=>'Phone not registered', 'data'=>[]);
	        return $message;
    	}
                
    	$checkUser = DB::table('users')
    					->where('user_phone', $user_phone)
    					->where('user_password', $user_password)
    					->first();

    	if($checkUser){
    	    
    	    if($checkUser->is_verified == 0){
    	        $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                
               $otpmsg = $this->otpmsg($otpval,$user_phone);
               
                $updateOtp = DB::table('users')
                                ->where('user_phone', $user_phone)
                                ->update(['otp_value'=>$otpval]);
                                
                $checkUser1 = DB::table('users')
            					->where('user_phone', $user_phone)
            					->first();                
                                
    	        $message = array('status'=>'2', 'message'=>'Verify Phone', 'data'=>[$checkUser1]);
	        	return $message;
    	    }
    	   else{
    		   $updateDeviceId = DB::table('users')
    		                        ->where('user_phone', $user_phone)
    		                        ->update(['device_id'=>$device_id]);
    		                       
    		   $checkUser1 = DB::table('users')
            					->where('user_phone', $user_phone)
            					->where('user_password', $user_password)
            					->first();
    		                        
    			$message = array('status'=>'1', 'message'=>'login successfully', 'data'=>[$checkUser1]);
	        	return $message;
    	   }	   
    	
    	}
    	else{
    		$message = array('status'=>'0', 'message'=>'Wrong Password', 'data'=>[]);
	        return $message;
    	}
    }
    
    
    
    
    public function myprofile(Request $request)
    {   
        $user_id = $request->user_id;
         $user =  DB::table('users')
                ->where('user_id', $user_id )
                ->first();
                        
    if($user){
        	$message = array('status'=>'1', 'message'=>'User Profile', 'data'=>$user);
	        return $message;
              }
    	else{
    		$message = array('status'=>'0', 'message'=>'User not found', 'data'=>[]);
	        return $message;
    	}
        
    }   
    
    public function forgotPassword(Request $request)
    {
        $user_phone = $request->user_phone;
        
        $checkUser = DB::table('users')
                        ->where('user_phone', $user_phone)
                        ->where('is_verified',1)
                        ->first();
                        
        if($checkUser){
                $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                
               $otpmsg = $this->otpmsg($otpval,$user_phone);
    
                $updateOtp = DB::table('users')
                                ->where('user_phone', $user_phone)
                                ->update(['otp_value'=>$otpval]);
                                
            if($updateOtp){
              $checkUser1 = DB::table('users')
            					->where('user_phone', $user_phone)
            					->first();
    		                        

                $data[]   =   (object)[
                                "user_id"           =>  (string)$checkUser1->user_id,
                                "user_name"         =>  (string)$checkUser1->user_name,
                                "user_phone"        =>  (string)$checkUser1->user_phone,
                                "user_email"        =>  (string)$checkUser1->user_email,
                                "device_id"         =>  (string)$checkUser1->device_id,
                                "user_image"        =>  (string)$checkUser1->user_image,
                                "user_password"     =>  (string)$checkUser1->user_password,
                                "otp_value"         =>  (string)$checkUser1->otp_value,
                                "status"            =>  (string)$checkUser1->status,
                                "wallet"            =>  (string)$checkUser1->wallet ? round($checkUser1->wallet) : '',
                                "rewards"           =>  (string)$checkUser1->rewards,
                                "is_verified"       =>  (string)$checkUser1->is_verified,
                                "block"             =>  (string)$checkUser1->block,
                                "reg_date"          =>  (string)$checkUser1->reg_date
                            ];

    			$message = array('status'=>'1', 'message'=>'Verify OTP', 'data'=>$data);
	        	return $message; 
            }
            else{
                $message = array('status'=>'0', 'message'=>'Something wrong', 'data'=>[]);
	        	return $message; 
            }
        }                
        else{
            $message = array('status'=>'0', 'message'=>'User not registered', 'data'=>[]);
	        return $message;
        }
        
    }
    
    public function verifyOtp(Request $request)
    {
        $phone = $request->user_phone;
        $otp = $request->otp;
        
        // check for otp verify
        $getUser = DB::table('users')
                    ->where('user_phone', $phone)
                    ->first();
                    
        if($getUser){
            $getotp = $getUser->otp_value;
            
            if($otp == $getotp){
                $message = array('status'=>1, 'message'=>"Otp Matched Successfully");
                return $message;
            }
            else{
                $message = array('status'=>0, 'message'=>"Wrong OTP");
                return $message;
            }
        }
        else{
            $message = array('status'=>0, 'message'=>"User not registered");
            return $message;
        }
    }
    
    public function changePassword(Request $request)
    {
        $user_phone = $request->user_phone;
        $password = $request->user_password;
        
        $getUser = DB::table('users')
                    ->where('user_phone', $user_phone)
                    ->first();
                    
        if($getUser){
            $updateOtp = DB::table('users')
                            ->where('user_phone', $user_phone)
                            ->update(['user_password'=>$password]);
                                
            if($updateOtp){
              $checkUser1 = DB::table('users')
            					->where('user_phone', $user_phone)
            					->first();
    		                        
    			$message = array('status'=>'1', 'message'=>'Password changed', 'data'=>[$checkUser1]);
	        	return $message; 
            }
            else{
                $message = array('status'=>'0', 'message'=>'Something wrong', 'data'=>[]);
	        	return $message; 
            }
        }
        else{
            $message = array('status'=>0, 'message'=>"User not registered");
            return $message;
        }
    }
    
    
     public function profile_edit(Request $request)
    {
        $user_id = $request->user_id;
    	$user_name = $request->user_name;
    	$user_email = $request->user_email;
    	$user_phone = $request->user_phone;
    	$user_image = $request->user_image;
    		$uu = DB::table('users')
    	    ->where('user_id', $user_id)
    	    ->first();
    	$user_password = $uu->user_password;
        // $date=date('d-m-Y');
    	    
    	   if($request->user_image){
            $user_image = $request->user_image;
            $user_image = str_replace('data:image/png;base64,', '', $user_image);
            $fileName = str_replace(" ", "-", $user_image);
            $fileName = date('dmyHis').'user_image'.'.'.'png';
            $fileName = str_replace(" ", "-", $fileName);
            \File::put(public_path(). '/images/user/' . $fileName, base64_decode($user_image));
            $user_image = 'images/user/'.$fileName;
        }
            else{
                $user_image = 'N/A';
            }
        
        $checkUser = DB::table('users')
    			->where('user_phone', $user_phone)
    			->where('user_id','!=', $user_id)
    			->first();
    	if($checkUser && $checkUser->is_verified==1){
    		$message = array('status'=>'0', 'message'=>'This Phone number is attached with another account');
            return $message;
    	}
    	
        else{
        
    		$insertUser = DB::table('users')
    		            ->where('user_id', $user_id)
    						->update([
    							'user_name'=>$user_name,
    							'user_email'=>$user_email,
    							'user_phone'=>$user_phone,
    							'user_image'=>$user_image,
    							'user_password'=>$user_password,
    						]);
    						
            	$Userdetails = DB::table('users')
    					->where('user_id', $user_id)
    					->first();
    					
    					
    		if($insertUser){
    						
	    		$message = array('status'=>'1', 'message'=>'Profile Updated', 'data'=>$Userdetails);
	        	return $message;
	    	}
	    	else{
	    		$message = array('status'=>'0', 'message'=>'Something Went wrong');
	        return $message;
	    	}  
    	}
    }
    
      public function user_block_check(Request $request)
    {   
        $user_id = $request->user_id;
         $user =  DB::table('users')
                ->select('block')
                ->where('user_id', $user_id )
                ->first();
                        
    if($user){
        if($user->block==1){
        	$message = array('status'=>'1', 'message'=>'User is Blocked');
	        return $message;
        }else{
            	$message = array('status'=>'2', 'message'=>'User is Active');
	        return $message;
            }
         }
    	else{
    		$message = array('status'=>'0', 'message'=>'User not found');
	        return $message;
    	}
        
    }   
    
    //added code for paytm beg
    public function initiate()
    {
        return view('paytm');
    }
     public function pay(Request $request)
    {
        //dd('here');   
        $amount = 1500; //Amount to be paid
        //dd($request->all());     

        $userData = [
            'name' => $request->name, // Name of user
            'mobile' => $request->mobile, //Mobile number of user
            'email' => $request->email, //Email of user
            'fee' => $amount,
            'order_id' => $request->mobile."_".rand(1,1000) //Order id
        ];

        //$paytmuser = Paytm::create($userData); // creates a new database record

        $payment = PaytmWallet::with('receive');

        $payment->prepare([
            'order' => $userData['order_id'], 
           // 'user' => $paytmuser->id,
            'user' => 1,
            'mobile_number' => $userData['mobile'],
            'email' => $userData['email'], // your user email address
            'amount' => $amount, // amount will be paid in INR.
            'callback_url' => route('status') // callback URL
        ]);
        return $payment->receive();  // initiate a new payment
    }
    public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response();
        
        $order_id = $transaction->getOrderId(); // return a order id
      
        $transaction->getTransactionId(); // return a transaction id
    
        // update the db data as per result from api call
        if ($transaction->isSuccessful()) {
           // Paytm::where('order_id', $order_id)->update(['status' => 1, 'transaction_id' => $transaction->getTransactionId()]);
            return redirect(route('initiate.payment'))->with('message', "Your payment is successfull.");

        } else if ($transaction->isFailed()) {
           // Paytm::where('order_id', $order_id)->update(['status' => 0, 'transaction_id' => $transaction->getTransactionId()]);
            return redirect(route('initiate.payment'))->with('message', "Your payment is failed.");
            
        } else if ($transaction->isOpen()) {
            //Paytm::where('order_id', $order_id)->update(['status' => 2, 'transaction_id' => $transaction->getTransactionId()]);
            return redirect(route('initiate.payment'))->with('message', "Your payment is processing.");
        }
        $transaction->getResponseMessage(); //Get Response Message If Available
        
        // $transaction->getOrderId(); // Get order id
    }
    //added code for paytm end
    #added code for contact us beg
    public function contactus(Request $request){
          
           // if(isset($request->submit)){
              $insert_contact=DB::table('contact_us')
                              ->insert(['name' =>$request->fullname,'mobile'=>$request->phone,'email'=>$request->email,'message'=>$request->message]);
                #send message to admin beg

                     $data['html']='<p>'.$request->message.'</p>';
                  
                     $data['name']=$request->fullname;
                     $data['email']=$request->email;
                     $data['emailresponse']='<p>Your Query submittted succesfully.We will get you in touch soon</p>';
                      $allMails = [$request->email,'sfsdailyneeds@gmail.com']; 
            
              #code for email send to admin
             $a= Mail::send('email.contact_reply', ['data'=>$data], function ($message) use ($data,$allMails) {
                    $message->to($allMails, '')->subject
                    ('Feedback');
                    $message->from( $data['email'],  $data['name']);
                    $message->setBody($data['html'],'text/html');
                });

                 
                #code for email send to user
                Mail::send('email.contact_response_reply', ['data'=>$data], function ($message) use ($data) {
                    $message->to( $data['email'], '')->subject
                    ('Feedback');
                    $message->from('sfsdailyneeds@gmail.com','');
                    $message->setBody( $data['emailresponse'],'text/html');
                });
                
               
               
                 

                //return redirect('/contact')->with('message','Your Query Submitted Successfully');
               // return redirect()->back()->with('message','Your Query Submitted Successfully');

                return response()->json(['status'=>'1','message'=>'Your feedback submitted successfully']);

                #send message to admin end
           // }

          }
    #added code for contact us end
    
}
