<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use App\Traits\SendSms;

class UserController extends Controller
{
    use SendSms;

    
    public function signUp(Request $request)
    {
        
        $this->validate(
            $request, 
            [
                'user_name' => 'required',
                //'user_email' => 'required|email',
                'user_phone' => 'required'
            ],
            [
                'user_name.required' => 'Enter Name...',
                //'user_email.required' => 'Enter Email...',
                'user_phone.required' => 'Enter Mobile...',
            ]
        );
    	$user_name = $request->user_name;
    	//$user_email = $request->user_email;
    	$user_phone = $request->user_phone;
        $device_id = $request->device_id;
    	//$user_password = $request->user_password;
        //dd('adsa');

        // $user_name = 'Ravi';
        // $user_email = 'ravi@gmail.com';
        // $user_phone = 8377082873;
        // $device_id = 'asdj2343243';
    	
        $created_at = Carbon::now();
        $updated_at = Carbon::now();
        // $date=date('d-m-Y');
    	$checkUser = DB::table('users')
    					->where('user_phone', $user_phone)
    					->first();
        $smsby = DB::table('smsby')
              ->first();
        if($smsby->status==1){      
        // check for otp verify
    	if($checkUser && $checkUser->is_verified==1){
    		$message = array('status'=>'0', 'message'=>'User Already Registered', 'data'=>[]);
            return $message;
    	}
    	
        ///////if phone not verified/////	
    	elseif($checkUser && $checkUser->is_verified==0) {
	                 $delnot= DB::table('notificationby')
    						->where('user_id', $checkUser->user_id)
    				     	->delete();
    						
    	    	$delUser = DB::table('users')
    					->where('user_phone', $user_phone)
    					->delete();
    					
    	    
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
    							'user_name'=>$user_name,
    							'user_email'=>'',
    							'user_phone'=>$user_phone,
    							'user_image'=>$user_image,
    							'user_password'=>'123456',
                                'is_verified'=>1, //for prevent varification issue
    							'device_id'=>$device_id,
    							'reg_date'=>$created_at
    						]);
    						
            	$Userdetails = DB::table('users')
    					->where('user_phone', $user_phone)
    					->first();
    		if($insertUser){
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
                
                

                //Currently comment for twilo
                    
                    //$otpmsg = $this->otpmsg($otpval,$user_phone);
                    
                    //end comment for twilo
                
                $updateOtp = DB::table('users')
                                ->where('user_phone', $user_phone)
                                ->update(['otp_value'=>$otpval]);
                $Userdetails->otp= $otpval;                  
    						
	    		$message = array('status'=>'1', 'message'=>'OTP Sent', 'data'=>$Userdetails);
	        	return $message;
	    	}
	    	else{
	    		$message = array('status'=>'0', 'message'=>'Something Went Wrong');
	        return $message;
	    	}  
    	}
    	 ///////new user/////	
    	else{
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

            $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
        
    		$insertUser = DB::table('users')
    						->insertGetId([
    							'user_name'=>$user_name,
    							'user_email'=>'',
    							'user_phone'=>$user_phone,
    							'user_image'=>$user_image,
    							'user_password'=>'123456',
    							'device_id'=>$device_id,
    							'reg_date'=>$created_at,
                                'otp_value'=>$otpval
    						]);
    						
            	$Userdetails = DB::table('users')
    					->where('user_phone', $user_phone)
    					->first();
    		if($insertUser){
    		     DB::table('notificationby')
    						->insert(['user_id'=> $insertUser,
    						'sms'=> '1',
    						'app'=> '1',
    						'email'=> '1']);
    						
    						
    			
                

               //Currently comment for twilo
                    
                $otpmsg = $this->otpmsg($otpval,$user_phone);
                    
                    //end comment for twilo
    
                $updateOtp = DB::table('users')
                                ->where('user_phone', $user_phone)
                                ->update(['otp_value'=>$otpval]);
                $Userdetails->otp= $otpval;                 
    						
	    		$message = array('status'=>'1', 'message'=>'OTP Sent', 'data'=>$Userdetails);
	        	return $message;
	    	}
	    	else{
	    		$message = array('status'=>'0', 'message'=>'Something Went Wrong');
	        return $message;
	    	}
    	}
        }
        else{
        if($checkUser){
    		$message = array('status'=>'0', 'message'=>'User Already Registered');
            return $message;
    	}
    	else{
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

            $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
            
    		$insertUser = DB::table('users')
    						->insertGetId([
    							'user_name'=>$user_name,
    							'user_email'=>'',
    							'user_phone'=>$user_phone,
    							'user_image'=>$user_image,
    							'user_password'=>'123456',
    							'device_id'=>$device_id,
    							'reg_date'=>$created_at,
    							'is_verified'=>1,
                                'otp_value'=>$otpval
    						]);          
    						
            	$Userdetails = DB::table('users')
    					->where('user_phone', $user_phone)
    					->first();
    		if($insertUser){
                 $otpmsg = $this->otpmsg($otpval,$user_phone); 
    		     DB::table('notificationby')
    						->insert(['user_id'=> $insertUser,
    						'sms'=> '1',
    						'app'=> '1',
    						'email'=> '1']);
    			$message = array('status'=>'2', 'message'=>'OTP Send to verify & Login', 'data'=>$Userdetails);
                return $message;			
             	}
            }
        }
    }


    public function contactUs(Request $request)
    {
        
        $this->validate(
            $request, 
            [
                'fullname' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'message' => 'required'
            ],
            [
                'fullname.required' => 'Enter Full Name...',
                'email.required' => 'Enter email...',
                'phone.required' => 'Enter Mobile...',
                'message.required' => 'Enter message...',
            ]
        );

        $user_name = $request->fullname;
        $user_email = $request->email;
        $user_phone = $request->phone;
        $query_message = $request->message;



        $insert_contact=DB::table('contact_us')
                      ->insertGetId(['name' =>$user_name,'mobile'=>$user_phone,'email'=>$user_email,'message'=>$query_message]);
        if($insert_contact){
             $message = array('status'=>'1', 'message'=>'Message Submitted Successfully!');
                return $message;

        }else{
          $message = array('status'=>'0', 'message'=>'Something Went Wrong');
            return $message;  

        }              
        
    }
    
    public function verifyPhone(Request $request)
    {
        $phone = $request->user_phone;
        $otp = $request->otp;
        $smsby = DB::table('smsby')
              ->first();
        if($smsby->status==1){      
        // check for otp verify
        $getUser = DB::table('users')
                    ->where('user_phone', $phone)
                    ->first();
                    
        if($getUser){
            $getotp = $getUser->otp_value;
            
            /*if($otp == $getotp){*/
                $getUser = DB::table('users')
                            ->where('user_phone', $phone)
                            ->update(['is_verified'=>1,
                            'otp_value'=>NULL]);
                    
                $message = array('status'=>1, 'message'=>"Phone Verified! Login Successfully");
                return $message;
            /*}
            else{
                $message = array('status'=>0, 'message'=>"Wrong OTP");
                return $message;
            }*/
       
        }
        else{
            $message = array('status'=>0, 'message'=>"User Not Registered");
            return $message;
        }
        }
        else{
              $getUser = DB::table('users')
                            ->where('user_phone', $phone)
                            ->update(['is_verified'=>1,
                            'otp_value'=>NULL]);
             $message = array('status'=>1, 'message'=>"Phone Verified! Login Successfully");
            return $message;
        }
    }


    public function login1(Request $request)
    
     {
    	$user_phone = $request->user_phone;
    	$user_password = $request->user_password;
    	$device_id = $request->device_id;
    	
    	$checkUserReg = DB::table('users')
    					->where('user_phone', $user_phone)
    					->first();
    					
    	/*if(!($checkUserReg) || $checkUserReg->is_verified== 0){
    	    $message = array('status'=>'0', 'message'=>'Phone Not Verified', 'data'=>[]);
	        return $message;
    	}*/
                
    	$checkUser = DB::table('users')
    					->where('user_phone', $user_phone)
    					->where('user_password', $user_password)
    					->first();

    	if($checkUser){
    	    
    	    /*if($checkUser->is_verified == 0){
    	        $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                
               //Currently comment for twilo
                    
                    //$otpmsg = $this->otpmsg($otpval,$user_phone);
                    
                    //end comment for twilo
               
                $updateOtp = DB::table('users')
                                ->where('user_phone', $user_phone)
                                ->update(['otp_value'=>$otpval]);
                                
                $checkUser1 = DB::table('users')
            					->where('user_phone', $user_phone)
            					->first();                
                                
    	        $message = array('status'=>'2', 'message'=>'Verify Phone', 'data'=>[$checkUser1]);
	        	return $message;
    	    }
    	   else{*/
    		   $updateDeviceId = DB::table('users')
    		                        ->where('user_phone', $user_phone)
    		                        ->update(['device_id'=>$device_id]);
    		                       
    		   $checkUser1 = DB::table('users')
            					->where('user_phone', $user_phone)
            					->where('user_password', $user_password)
            					->first();
    		                        
    			$message = array('status'=>'1', 'message'=>'Login Successfully', 'data'=>[$checkUser1]);
	        	return $message;
    	   /*}*/	   
    	
    	}
    	else{
    		$message = array('status'=>'0', 'message'=>'Wrong Password', 'data'=>[]);
	        return $message;
    	}
    }

    public function login(Request $request)
    {
		//dd($request->all());
        $user_phone = $request->user_phone;
        //$user_phone = 8377082873;
        
        $checkUser = DB::table('users')
                        ->where('user_phone', $user_phone)
                        //->where('is_verified',1)
                        ->first();               
                        
        if($checkUser) {
            $chars  = "0123456789";
            $otpval = "";
            for ($i = 0; $i < 4; $i++){
                $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
            }
            //dd($otpval);
            //Currently comment for twilo
                    
            $otpmsg = $this->otpmsg($otpval,$user_phone);
                    
            //end comment for twilo

            $updateOtp = DB::table('users')
                            ->where('user_phone', $user_phone)
                            ->update(['otp_value'=>$otpval]);
                                
            //if($updateOtp){
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

                $message = array( 'status' => '1', 'message' => 'Verify OTP', 'data' => $data );

                return $message; 
            // } else {
                // $message = array( 'status' => '0', 'message' => 'Something Wrong', 'data' => [] );
                // return $message; 
            // }
        } else {
            $message = array('status' => '0', 'message' => 'User Not Registered', 'data' => [] );
            return $message;
        }
    }

    public function resendOtp(Request $request)
    {
        $user_phone = $request->user_phone;
        //$user_phone = 8377082873;
        
        $checkUser = DB::table('users')
                        ->where('user_phone', $user_phone)
                        //->where('is_verified',1)
                        ->first(); 
        //dd($checkUser->otp_value);                              
                        
        if($checkUser) {
            // $chars  = "0123456789";
            // $otpval = "";
            // for ($i = 0; $i < 4; $i++){
            //     $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
            // }
            
            //Currently comment for twilo

            $otpval = $checkUser->otp_value;
                    
            $otpmsg = $this->otpmsg($otpval,$user_phone);
                    
            //end comment for twilo

            // $updateOtp = DB::table('users')
            //                 ->where('user_phone', $user_phone)
            //                 ->update(['otp_value'=>$otpval]);
                                
            // if($updateOtp){
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

                $message = array( 'status' => '1', 'message' => 'OTP Resend Successfully!', 'data' => $data );

                return $message; 
            // } else {
            //     $message = array( 'status' => '0', 'message' => 'Something Wrong', 'data' => [] );
            //     return $message; 
            // }
        } else {
            $message = array('status' => '0', 'message' => 'User Not Registered', 'data' => [] );
            return $message;
        }
    }

    public function loginVerifyOtp(Request $request)
    {
        $phone = $request->user_phone;
        $otp = $request->otp;
        // $phone=8377082873;
        // $otp=2993;
        
        // check for otp verify
        $getUser = DB::table('users')
                    ->where('user_phone', $phone)
                    ->first();
                    
        if($getUser){
            $getotp = $getUser->otp_value;
            
            if($otp == $getotp){
                $message = array('status'=>'1','message'=>'Login Successfully', 'data'=>[$getUser]);
                return $message;
            }
            else{
                $message = array('status'=>'0', 'message'=>"Wrong OTP", 'data'=>[]);
                return $message;
            }
        }
        else{
            $message = array('status'=>'0', 'message'=>"User Not Registered", 'data'=>[]);
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
    		$message = array('status'=>'0', 'message'=>'User Not Found', 'data'=>[]);
	        return $message;
    	}
        
    }   
    
    public function forgotPassword(Request $request)
    {
        $user_phone = $request->user_phone;
        //$user_phone = 8377082873;
        
        $checkUser = DB::table('users')
                        ->where('user_phone', $user_phone)
                        //->where('is_verified',1)
                        ->first();
                        
        if($checkUser) {
            $chars  = "0123456789";
            $otpval = "";
            for ($i = 0; $i < 4; $i++){
                $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
            }
            
            //Currently comment for twilo
                    
            $otpmsg = $this->otpmsg($otpval,$user_phone);
                    
            //end comment for twilo

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

    			$message = array( 'status' => '1', 'message' => 'Verify OTP', 'data' => $data );

	        	return $message; 
            } else {
                $message = array( 'status' => '0', 'message' => 'Something Wrong', 'data' => [] );
	        	return $message; 
            }
        } else {
            $message = array('status' => '0', 'message' => 'User Not Registered', 'data' => [] );
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
                $message = array('status'=>1, 'message'=>"OTP Matched Successfully");
                return $message;
            }
            else{
                $message = array('status'=>0, 'message'=>"Wrong OTP");
                return $message;
            }
        }
        else{
            $message = array('status'=>0, 'message'=>"User Not Registered");
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
                $message = array('status'=>'0', 'message'=>'Something Wrong', 'data'=>[]);
	        	return $message; 
            }
        }
        else{
            $message = array('status'=>0, 'message'=>"User Not Registered");
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

            if(!$user_email){
                $user_email='';
            }
            if(!$user_password){
                $user_password='123456';
            }
        
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
	    		$message = array('status'=>'0', 'message'=>'Something Went Wrong');
	        return $message;
	    	}  
    	}
    }
    
      public function user_block_check(Request $request)
    {   
        $user_id = $request->user_id;
        //$user_id = '115';
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
    		$message = array('status'=>'0', 'message'=>'User Not Found');
	        return $message;
    	}
        
    }   
    
}
