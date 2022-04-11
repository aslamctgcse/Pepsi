<?php

namespace App\Http\Controllers\Website;

use DB;
use Session;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;
use App\Traits\SendSms;
//use App\Traits\SendSms;  

class SignupController extends Controller
{
    use SendSms;
    # Bind the website path.
    protected $viewPath = 'website.login_register.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param 
     * @return \Illuminate\Http\Response
     */
    public function showLoginRegisterModal()
    {
       // dd('here register');
        # Display the login or register pop up.
        return view($this->viewPath.'index');
    }

    /**
     * Login Or Register New User in app.
     *
     * @param 
     * @return \Illuminate\Http\Response
     */
    public function loginRegister(Request $request)
    {
        //dd($request->all());
        # check type is login or register.
        if ($request->get('type') == 'login') {

            # Gather the form data.
            //dd($request->all());
            $password       = $request->get('password');
            $mobileNumber   = $request->get('mobile_number');

            # check if it is mobile number or not.
            if (is_numeric($mobileNumber)) {

                # get data if user already exists
                $checkUser  =   DB::table('users')
                                    ->where('user_phone', $mobileNumber)
                                    ->where('user_password', $password)
                                    ->first();
            } else {

                # get data if user already exists
                $checkUser  =   DB::table('users')
                                    ->where('user_email', $mobileNumber)
                                    ->where('user_password', $password)
                                    ->first();
            }

            # check user is login or not.
            if ($checkUser) {
                # set session if user is login.
                Session::put('userData', $checkUser);
                Session::save();

                # return success message after login.
                // return  [
                //             'success'   => 200, 
                //             'message'   => 'User Login successfully!', 
                //             'data'      => Session::get('userData'),
                //             'redirect'  => route('my-profile')
                //         ];
               // return redirect('/my-profile')->with('message', 'Loginned Successfully');
                 $message = array('status'=>'1', 'message'=>'Login Successfully');
                return $message;
                
            } else {
                # return error message if user credentials are not matched.
                //return ['error' => 404, 'message' => 'Your credentials are not matched!'];
                //return redirect('/')->with('message', 'Invalid Credential.please try again');
                 $message = array('status'=>'0', 'message'=>'Invalid Credential. Please try again');
                return $message;
            }

        } else {
           

            # Gather the form data.
            $userName       =  $request->get('full_name');
            $password       =  $request->get('password');
            $cPassword      =  $request->get('c_password');
            $mobileNumber   =  $request->get('mobile_number');
            $registerEmail      =  $request->get('user_email');
            if(!$registerEmail){
              $registerEmail ='';
            }
            

            # get current date.
            $toDay  =   Carbon::now();

            # set the register user data.
            $formData   =   [
                                'user_name'         =>  $userName,
                                'user_image'        =>  'N/A',
                                'user_password'     =>  $password,
                                'user_email'        =>  $registerEmail,
                                'block'             =>  2,
                                'status'            =>  1,
                                //'is_verified'       =>  0,
                                'is_verified'       =>  1, //auto verified till sms by Anish[04-0]
                                'reg_date'          =>  $toDay,
                            ];

            # check if it is mobile number or not.
            if (is_numeric($mobileNumber)) {

                # get data if user already exists
                $checkUser  =   DB::table('users')
                                    ->where('user_phone', $mobileNumber)
                                    //->where('is_verified','=',0) //added to check user exist 21 nov
                                    ->first();

                $formData['user_phone']   =  $request->get('mobile_number');
            } 
            if($registerEmail){

                # get data if user already exists
                $checkUser  =   DB::table('users')
                                    ->where('user_email', $registerEmail)
                                    //->where('is_verified','=',0) //added to check user exist 21 nov
                                    ->first();

                $formData['user_email']   =  $request->get('user_email');
            }

            if ($checkUser) {

                # set session if user is login.
               /* Session::put('userData', $checkUser);
                Session::save();*/

                # return success message after login.
                // return  [
                //             'success'   => 200,
                //             'message'   => 'User Login successfully!',
                //             'data'      => Session::get('userData'),
                //             'redirect'  => route('my-profile')
                //         ];
                       // return redirect('/')->with('message', 'Registered Successfully.Please login'); 
              
              //return redirect('/')->with('message', 'User with this mobile already exists'); 
             /* $message = array('status'=>'0', 'message'=>'User with this mobile already exists','otpval'=>'');*/ //previous
             #added 21 nov beg
              //added code to update otp code in table and send otp in email beg
                $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                $otpmsg = $this->otpmsgregister($otpval,$mobileNumber);
                $updateOtp = DB::table('users')
                                ->where('user_phone',$request->get('mobile_number'))
                                  ->where('is_verified','=',0) //added to check user exist 21 nov
                                ->update(['otp_value'=>$otpval]);
                                # set session if user is login.
                  // comment on 27 july                
                //Session::put('userData', $checkUser);
                //Session::save();

                //added code to update otp code in table and send otp in email end
             #added 21 nov end
               //$message = array('status'=>'1', 'message'=>'Please verify otp','otpval'=>$otpval);
                 $message = array('status'=>'0', 'message'=>'Mobile number or Email already registered, Please login or registered with different mobile number','otpval'=>$otpval);                 
                return $message; 
            } else {

                # create new user 
                $userId     = DB::table('users')->insertGetId($formData);
                $checkUser  = DB::table('users')->where('user_id', $userId)->first();

               

                # set session if user is login.
               /* Session::put('userData', $checkUser);
                Session::save();*/ //block on 9 nov night

                # return success message after login.
                // return  [
                //             'success'   => 200,
                //             'message'   => 'User Login successfully!',
                //             'data'      => Session::get('userData'),
                //             'redirect'  => route('my-profile')
                //         ];
                //added code to update otp code in table and send otp in email beg
                $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                $otpmsg = $this->otpmsgregister($otpval,$mobileNumber);
                $updateOtp = DB::table('users')
                                ->where('user_id', $userId)
                                ->update(['otp_value'=>$otpval]);
                //added code to update otp code in table and send otp in email end
                # set session if user is login.
                Session::put('userData', $checkUser);
                Session::save();                

                 $message = array('status'=>'1', 'message'=>'Please verify otp','otpval'=>$otpval);
                return $message; 
            }

            # redirect to profile page.
           // return 1;
            // return route->redirect($this->userPath.'profile');
           // return redirect('/')->with('msg', 'Registered Successfully.Please login'); 
        }

        # Display the login or register pop up.
        return view($this->viewPath.'index');
    }

    /**
     * Logout Login User
     *
     * @param 
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        # Session logout.
        Session::forget('userData');
        Session::save();

        # Display the login or register pop up.
        return redirect()->route('/');
    }
    //added code for forgot pass beg
    public function forgotPassword(Request $request){
       $user_phone = $request->user_mobile;
       //$user_phone = 8377082873;
        
        $checkUser = DB::table('users')
                        ->where('user_phone', $user_phone)
                        ->where('is_verified',1)
                        ->first();
        //dd($checkUser->user_name);                
        if($checkUser){
                $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
               $password=$checkUser->user_password;
               $user_name=$checkUser->user_name;
               

                
              // $otpmsg = $this->sendpasswordotp($password,$user_phone); //this is fn call for send otp
               $otpmsg = $this->sendpasswordotp($otpval,$user_phone,$user_name); //this is fn call for send otp
               //update otp in users table beg 8 nov
                $updateOtp = DB::table('users')
                                ->where('user_phone', $user_phone)
                                ->update(['otp_value'=>$otpval]);
               //update otp in users table beg 8 nov
                
         
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

                $message = array('status'=>'1', 'message'=>'OTP has been send to your mobile Number', 'data'=>$data,'otpmsg'=>$otpmsg,'password'=>$password,'verify_mobile'=>$user_phone,'otp'=>$otpval);
                return $message; 
           
        }else{
            $message = array('status'=>'0', 'message'=>'Invalid Credential. Please try again');
                return $message; 
        }                
        

    }
    //added code for forgot pass end
    //added code to send otp msg beg
   /* public function sendpassword($otpval,$user_phone) {
        $getInvitationMsg = "Your password is: ".$otpval.".\nNote: Please DO NOT SHARE this password with anyone."; 
        $smsby =  DB::table('smsby')
               ->first();
        if($smsby->status==1){         
        if($smsby->msg91==1){       
         $sms_api_key=  DB::table('msg91')
                      ->select('api_key', 'sender_id')
                      ->first();
                        $api_key = $sms_api_key->api_key;
                        $sender_id = $sms_api_key->sender_id;
                        $getAuthKey = $api_key;
                        $getSenderId = $sender_id;
                        
                        $authKey = $getAuthKey;
                        $senderId = $getSenderId;
                        $message1 = $getInvitationMsg;
                        $route = "4";
                        $postData = array(
                            'authkey' => $authKey,
                            'mobiles' => $user_phone,
                            'message' => $message1,
                            'sender' => $senderId,
                            'route' => $route
                        );
        
                        $url="https://control.msg91.com/api/sendhttp.php";
        
                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => $postData
                        ));

                //Ignore SSL certificate verification
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                //get response
                $output = curl_exec($ch);

                curl_close($ch);
        }else{
      
       $twilio=DB::table('twilio')
             ->first();
                           
       $twilsid = $twilio->twilio_sid;  
       $twiltoken = $twilio->twilio_token; 
       $twilphone = $twilio->twilio_phone; 
         // send SMS
        // Your Account SID and Auth Token from twilio.com/console
        $sid = $twilsid;
        $token = $twiltoken;
        $client = new Client($sid, $token);
        $user = $user_phone;
        // Use the client to do fun stuff like send text messages!
        $client->messages->create(
            // the number you'd like to send the message to
            $user,
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $twilphone,
                // the body of the text message you'd like to send
                'body' => $getInvitationMsg
               
            )
        );
        }
        }          
    }*/
    //added code to send otp msg end otpmsg
    //added code to verify otp registeration beg
    public function otpmsg($otpval,$user_phone) {
        $getInvitationMsg = "Your OTP is: ".$otpval.".\nNote: Please do not share this OTP with anyone."; 
        $smsby =  DB::table('smsby')
               ->first();
        if($smsby->status==1){         
        if($smsby->msg91==1){       
         $sms_api_key=  DB::table('msg91')
                      ->select('api_key', 'sender_id')
                      ->first();
                        $api_key = $sms_api_key->api_key;
                        $sender_id = $sms_api_key->sender_id;
                        $getAuthKey = $api_key;
                        $getSenderId = $sender_id;
                        
                        $authKey = $getAuthKey;
                        $senderId = $getSenderId;
                        $message1 = $getInvitationMsg;
                        $route = "4";
                        $postData = array(
                            'authkey' => $authKey,
                            'mobiles' => $user_phone,
                            'message' => $message1,
                            'sender' => $senderId,
                            'route' => $route
                        );
        
                        $url="https://control.msg91.com/api/sendhttp.php";
        
                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => $postData
                        ));

                //Ignore SSL certificate verification
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                //get response
                $output = curl_exec($ch);

                curl_close($ch);
        }else{
      
       $twilio=DB::table('twilio')
             ->first();
                           
       $twilsid = $twilio->twilio_sid;  
       $twiltoken = $twilio->twilio_token; 
       $twilphone = $twilio->twilio_phone; 
         // send SMS
        // Your Account SID and Auth Token from twilio.com/console
        $sid = $twilsid;
        $token = $twiltoken;
        $client = new Client($sid, $token);
        $user = $user_phone;
        // Use the client to do fun stuff like send text messages!
        $client->messages->create(
            // the number you'd like to send the message to
            $user,
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $twilphone,
                // the body of the text message you'd like to send
                'body' => $getInvitationMsg
               
            )
        );
        }
        }          
    }
    //added code to verify otp registeration end
    //added code to verify otp beg
     public function verifyotp(Request $request)
    {
        $phone = $request->user_mobile;
        $otp = $request->otp;
        
        // check for otp verify
        $getUser = DB::table('users')
                    ->where('user_phone', $phone)
                    ->first();
                    
        if($getUser){
            $getotp = $getUser->otp_value;
            
            if($otp == $getotp){
              //addedd to set user session after verify register 8 nov beg
               //$userId     = DB::table('users')->insertGetId($formData);
                #added code update otp_verify status 0 to 1 after otp verification beg
                 DB::table('users')->where('user_phone', $phone)
                                   ->where('is_verified','=',0)
                                   ->update(['is_verified'=>1]);
                #added code update otp_verify status 0 to 1 after otp verification end
                $checkUser  = DB::table('users')->where('user_phone', $phone)
                 ->where('is_verified','=',1)
                ->first();

               

                # set session if user is login.
                Session::put('userData', $checkUser);
                Session::save();
              //addedd to set user session after verify register 8 nov end
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
    //added code to verify otp end
    //added code to verify otp  for pass set beg
     public function forgetOtpVeify(Request $request)
    {
        $phone = $request->user_mobile;
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
    //added code to verify otp pass set end
    //added code to set new password beg
    public function newpassword(Request $request){
      /*user_mobile:user_mobile,
                otp:verify_otp_value,
                user_new_pass:user_new_pass,
                user_new_cpass:user_new_cpass*/
        $user_mobile=$request->user_mobile;
        $user_new_pass=$request->user_new_pass;
       // $otp=$request->verify_otp_value;
        $sepass=DB::table('users')
               ->where('user_phone','=',$user_mobile)
              // ->where('otp_value','=',$otp)
               ->update(['user_password'=>$user_new_pass]);
        if($sepass ==1 || $sepass == 0 || $sepass ==2){
         return response()->json(['status'=>1,'message'=>'Your New Password has been updated. Please login','sepass'=>$sepass]);
        }else{
           return response()->json(['status'=>0,'message'=>'User not Registerd','sepass'=>$sepass]);
        }

    }
    //added code to set new password end

    #added code to resend otp beg forgot pass
    public function resendotpforgotpass(Request $request){
        $mobileNumber=$request->user_mobile;
        // $otpdata=DB::table('users')
        //        ->where('user_phone','=',$mobileNumber)
        //        ->first();

                $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++){
                    $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                $updateOtp = DB::table('users')
                                ->where('user_phone',$mobileNumber)
                                ->update(['otp_value'=>$otpval]);

        //$otpval=$otpdata->otp_value;

        //if(isset( $otpval)){
        if($updateOtp){
        
        $otpmsg = $this->otpmsgregister($otpval,$mobileNumber);
         return response()->json(['status'=>1,'message'=>'OTP has been sent','otp'=>$otpval]);
      }else{
        return response()->json(['status'=>0,'message'=>'Error occured. Try again','otp'=>'']);
      }
    }
    #added code to resend otp forgot pass end


     #added code to resend otp 
    public function resendotp(Request $request)
    {
        $mobileNumber=$request->user_mobile;
        $chars = "0123456789";
        $otpval = "";
        for ($i = 0; $i < 4; $i++){
            $otpval .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        $updateOtp = DB::table('users')
                        ->where('user_phone',$mobileNumber)
                        ->update(['otp_value'=>$otpval]);
        if($updateOtp){
            $otpmsg = $this->otpmsgregister($otpval,$mobileNumber);
            return response()->json(['status'=>1,'message'=>'OTP has been sent','otp'=>$otpval]);
        }else{
           return response()->json(['status'=>0,'message'=>'Error occured. Try again','otp'=>'']);
        }
    }
    #added code to resend otp end
}
