
 
   <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <div class="login-modal">
               <div class="row no-gutters">
                  <div class="col-lg-6 login-bg">
                     <div class="login-modal-left">
                        
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <button type="button" class="close close-top-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                           <i class="mdi mdi-close"></i>
                        </span>
                        <span class="sr-only">Close</span>
                     </button>

                        <div class="login-modal-right">
                           <!-- Tab panes -->
                           <div class="tab-content">
                                 
                              <div class="tab-pane active" id="login" role="tabpanel">
                                 <form action="{{ route('login-register') }}" method="post" id="login-form">
                                    {{csrf_field()}}
                                    <h5 class="heading-design-h5">Login to your account</h5>
                                    <h5 id="login-msg" style="color: red;"></h5>
                                    <fieldset class="form-group">
                                       <label>Enter Mobile number <span class="text-danger">*</span></label>
                                       <input type="text" class="form-control" name="mobile_number" placeholder="Enter Mobile number" id="mobile_number" maxlength="10" required>
                                       <span style="color:red;position: absolute;margin-top: 
                                       -5px;right:25px;" id="login_number_error"></span>
                                    </fieldset>

                                    <fieldset class="form-group mb-4">
                                       <label>Enter Password <span class="text-danger">*</span></label>
                                       <input type="password" class="form-control" name="password" placeholder="Enter Password" id="login_pass" maxlength="20" required>
                                       <span id="pass_error" style="color:red;position: absolute;margin-top:-5px;right:25px;"></span>
                                    </fieldset>

                                    <fieldset class="form-group">
                                      <!--  <button type="button" class="btn btn-lg btn-secondary btn-block" id="login-submit">Enter to your account</button> -->
                                      <input type="submit" class="btn btn-lg btn-secondary btn-block" id="login-submit" name="submit" value="Enter to your account"  id="#enter_account"/>
                                    </fieldset>

                                    <div class="login-with-sites text-center" style="display: none;">
                                       <p>or Login with your social profile: </p>
                                       <button class="btn-facebook login-icons btn-lg"><i class="mdi mdi-facebook"></i> Facebook</button>
                                       <button class="btn-google login-icons btn-lg"><i class="mdi mdi-google"></i> Google</button>
                                       <button class="btn-twitter login-icons btn-lg"><i class="mdi mdi-twitter"></i> Twitter</button>
                                    </div>
                                    
                                   <!--  <div class="custom-control custom-checkbox">
                                       <input type="hidden" class="form-control" name="type" value="login">
                                       <input type="checkbox" class="custom-control-input" id="customCheck1">
                                       <label class="custom-control-label" for="customCheck1">Remember me</label>
                                    </div> -->
                                    <!-- added code for forget password beg-->
                                    <div class="custom-control custom-checkbox">
                                       
                                       <a data-toggle="tab" href="#forgetpasswordaz" id="forgettab" role="tab">Forgot Password ?</a>
                                    </div>
                                    <!-- added code for forget password end-->
                                 </form>
                              </div>
                              <!--added code for forgot pass tab otp beg-->
                              <div class="tab-pane" id="forgetpassword" role="tabpanel">
                                 <div class="password_sent" style="color:green;text-align:center;"></div>
                                 <div class="password_sent_error" style="color:red;text-align:center;"></div>
                                  <form action="{{url('forgot-password')}}" method="post" id="forgot-password-form">
                                    {{csrf_field()}}
                                    
                                    <fieldset class="form-group mb-4">
                                       <label>Mobile Number</label>
                                       <input type="text" class="form-control" name="user_mobile" placeholder="Enter Mobile Number" id="forget_mobile" maxlength="10" required>
                                       <span id="forgot_mobile_error" style="color:red;position: absolute;margin-top:-5px;right:25px;"></span>
                                       
                                    </fieldset>

                                    <fieldset class="form-group">
                                       
                                       <input type="submit" class="btn btn-lg btn-secondary btn-block" id="forgot-submits" name="forgot_password" value="Verify" />
                                    </fieldset>
                                 </form>
                              </div>
                              <!--added code for forgot pass tab otp end-->
                              <!-- added code to verify otp of forget pass beg-->
                              <div class="tab-pane" id="verify_pass_otp" role="tabpanel">
                                 <div class="forget_otp_veify" style="color:green;text-align:center;"></div>
                                  <form action="{{url('forget_otp_veify')}}" method="post" id="forget-otp-form">
                                    {{csrf_field()}}
                                    
                                    <fieldset class="form-group mb-4">
                                       <label>OTP Verify</label>
                                       <input type="text" class="form-control" name="pass_v_otp" placeholder="Verify otp" id="pass_v_otp" maxlength="4" required="">
                                       <span id="forgot_otp_error" style="color:red;position: absolute;margin-top:-5px;right:25px;"></span>
                                       <p id="register_otp"></p>
                                         <!--added code to resend otp for forget password beg-->
                                        <span id="resend_otp" class="text-primary" onclick="resendotpforgetpass()" style="float: right; cursor: pointer;">Resend otp</span>                                   
                                        <!--added code to resend otp for forget password end-->
                                    </fieldset>
                                    

                                    <fieldset class="form-group">
                                       
                                       <input type="submit" class="btn btn-lg btn-secondary btn-block" id="otp_v_submit" name="otp_v_submit" value="Submit" />
                                    </fieldset>
                                 </form>
                              </div>
                              <!-- added code to verify otp of forget pass end-->
                              <!--adde code to show pass and new password set form beg -->
                              <div class="tab-pane" id="setpassword" role="tabpanel">
                                 <div class="setpass_msg" style="color:green;text-align:center;"></div>
                                  <form action="{{url('new-password')}}" method="post" id="set-password-form" style="">
                                    {{csrf_field()}}
                                    
                                    <fieldset class="form-group">
                                       <label>New Password <span class="text-danger">*</span></label>
                                       <input type="password" class="form-control" name="user_new_pass" placeholder="Enter Password" id="user_new_pass" maxlength="20" required>
                                       <span id="forgot_new_password_error" style="color:red;position: absolute;margin-top:-5px;right:25px;"></span>
                                    </fieldset>
                                    <fieldset class="form-group mb-4">
                                       <label>Confirm Password <span class="text-danger">*</span></label>
                                       <input type="password" class="form-control" name="user_new_cpass" placeholder="Enter Password" id="user_new_cpass" maxlength="20" required>
                                       <span id="forgot_conform_password_error" style="color:red;position: absolute;margin-top:-5px;right:25px;"></span>
                                    </fieldset>

                                    <fieldset class="form-group">
                                       
                                       <input type="submit" class="btn btn-lg btn-secondary btn-block" id="forgot-submit" name="confirm_pass" value="Submit" />
                                    </fieldset>
                                 </form>
                              </div>
                              <!--adde code to show pass and new password set form end -->
                              

                              <div class="tab-pane" id="register" role="tabpanel">
                                @if(session()->has('message'))
                                      <div class="alert alert-success">
                                  {{ session()->get('message') }}
                                 </div>
                                 @endif
                                 <form action="{{ route('login-register') }}" method="post" id="register-form">
                                    {{csrf_field()}}
                                    <h5 class="heading-design-h5">Register Now!</h5>
                                    <h6 id="register-msg" style="color: red;"></h6>
                                    <fieldset class="form-group">
                                       <label>Full Name <span class="text-danger">*</span></label>
                                       <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Enter name" maxlength="40" required>
                                       <span style="color:red;position: absolute;margin-top:-5px;right:25px;" id="reg_name_error"></span>
                                    </fieldset>

                                    <fieldset class="form-group">
                                       <label>Enter Mobile number <span class="text-danger">*</span></label>
                                       <input type="text" class="form-control" name="mobile_number" id="reg_mobile_number"  placeholder="Enter Mobile Number" id="registered_number" maxlength="10" required>
                                       <span style="color:red;position: absolute;margin-top:-5px;right:25px;" id="reg_no_error"></span>
                                    </fieldset>

                                    <fieldset class="form-group">
                                       <label>Enter Email Address <span class="text-danger">*</span></label>
                                       <input type="text" class="form-control" name="mobile_email" id="reg_email_address"  placeholder="Enter Email Address" maxlength="50" required>
                                       <span style="color:red;position: absolute;margin-top:-5px;right:25px;" id="reg_email_address_error"></span>
                                    </fieldset>

                                    <fieldset class="form-group">
                                       <label>Enter Password <span class="text-danger">*</span></label>
                                       <input type="password" class="form-control" name="password" placeholder="********" id="reg_pass" maxlength="20" required>
                                       <span style="color:red;position: absolute;margin-top:-5px;right:25px;" id="reg_error"></span>
                                    </fieldset>

                                    <fieldset class="form-group mb-4">
                                       <label>Enter Confirm Password <span class="text-danger">*</span></label> 
                                       <input type="password" class="form-control" name="c_password" id="c_password" placeholder="********" id="reg_confirm_pass" maxlength="20" required>
                                       <span style="color:red;position: absolute;margin-top:-5px;right:25px;" id="reg_confirm_error"></span>
                                    </fieldset>

                                    <!-- <div class="custom-control custom-checkbox">
                                       <input type="hidden" class="form-control" name="type" value="register" id="reg">
                                       <input type="checkbox" class="custom-control-input" id="customCheck2">
                                       <label class="custom-control-label" for="customCheck2">I Agree with 
                                          <a href="javascript::void(0)">Term and Conditions</a>
                                       </label>
                                    </div> -->
                                    <!--swati mam cod-->
                                    <div class="custom-control custom-checkbox">
                                       <input type="hidden" class="form-control" name="type" value="register">
                                       <input type="checkbox" class="custom-control-input checkbox_class" id="customCheck2" required>
                                       <label class="custom-control-label" for="customCheck2">I Agree with 
                                          <span data-toggle="modal" data-target="#termcondition" class="color-theme">Terms and Conditions</span>
                                       </label>
                                    </div>
                                    <!--swati mam cod-->

                                    <fieldset class="form-group">
                                       <!-- <button type="button" class="btn btn-lg btn-secondary btn-block" id="register-submit">Create Your Account </button> -->
                                       <input type="submit" class="btn btn-lg btn-secondary btn-block" id="register-submit" name="register" value="Create Your Account" />
                                    </fieldset>
                                 </form>
                                 <!--added code to show verify otp form beg-->
                                 <div class="tab-pane" id="verify_otp" role="" style="display:none;">
                                  <div class="otp_match_or_not" style="color:green;text-align:center;"></div>
                                  <!--error msg for resend otp beg-->
                                    <p class="otp_resend_or_not" style="text-align: center;"></p>
                                  <!--error msg for resend otp end-->
                                  <form action="{{url('verify-otp')}}" method="post" id="verify_otp_form">
                                    {{csrf_field()}}
                                    
                                    <fieldset class="form-group">
                                       <label>OTP</label>
                                       <input type="text" class="form-control" name="user_otp" placeholder="Enter Your OTP" id="verify_otp_value"  maxlength="4" required="">
                                       <span style="color:red;" id="verify_reg_error"></span>
                                       <input type="hidden" name="user_mobile" value="" id="verify_mobile" />
                                       <p id="register_verify_otp"></p>
                                       
                                       <!--added code for resend otp link beg-->
                                     <span id="resend_otp" class="text-primary" onclick="resendotp()" style="float: right; cursor: pointer;">Resend otp</span>
                                    <!--added code for resend otp link end-->
                                    </fieldset>


                                    <fieldset class="form-group">
                                       
                                       <input type="submit" class="btn btn-lg btn-secondary btn-block" id="verify" name="Verify" value="Verify" />
                                    </fieldset>
                                 </form>
                              </div>
                              <!--added code to show verify otp form end-->
                              </div>

                           </div>
                           <div class="clearfix"></div>

                           <div class="text-center login-footer-tab">
                              <ul class="nav nav-tabs" role="tablist">
                                 <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#login" role="tab" id="loginbtn">
                                       <i class="mdi mdi-lock"></i> LOGIN
                                    </a>
                                 </li>

                                 <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#register" role="tab" id="reg_tab">
                                       <i class="mdi mdi-pencil"></i> REGISTER
                                    </a>
                                 </li>
                              </ul>
                           </div>
                           <div class="clearfix"></div>
                        </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
 
<!-- </div> -->
<!--term and condtion-->


<!-- Login/Register Login Pop up -->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script>
$(document).ready(function(){
   $('#mobile_number').keyup(function (){

    var mobile=$('#mobile_number').val();
    //alert(mobile);
    if(isNaN(mobile)){
      $('#login_number_error').text('Only Numbers are allowed');
      //$('#login-submit').prop('disabled', true);
       if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
      
    }else{
      $('#login_number_error').text('');
      //$('#login-submit').prop('disabled', false);
    }

    if(mobile.length > 10){
       $('#login-submit').prop('disabled', true);
       $('#login_number_error').text('Mobile no. must not exceed 10 digit');
    }else if(mobile.length < 10 ){
         $('#login-submit').prop('disabled', true);
          $('#login_number_error').text('Mobile no. must be of 10 digit');
    }else{
         $('#login-submit').prop('disabled', false);
         $('#login_number_error').text('');
    }
   

   });
   //added code for validation of forget_mobile only number beg
   $('#forget_mobile').keyup(function (){
        var forgotMobile=$('#forget_mobile').val();
        if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
        if(forgotMobile.length > 10){
           $('#forgot-submits').prop('disabled', true);
           $('#forgot_mobile_error').text('Mobile no. must not exceed 10 digit');
        }else if(forgotMobile.length < 10 ){
         $('#forgot-submits').prop('disabled', true);
         $('#forgot_mobile_error').text('Mobile no. must be of 10 digit');
      }else{
         $('#forgot-submits').prop('disabled', false);
         $('#forgot_mobile_error').text('');
      }
     });
   $('#pass_v_otp').keyup(function (){
       var otp=$('#pass_v_otp').val();
       if(isNaN(otp)){
        if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
       }else{
        $('#pass_v_otp').text('');
      }

      if(otp.length > 4){
           $('#otp_v_submit').prop('disabled', true);
           $('#forgot_otp_error').text('OTP must not exceed 4 digit');
         }else if(otp.length < 4 ){
           $('#otp_v_submit').prop('disabled', true);
           $('#forgot_otp_error').text('OTP must be of 4 digit');
         }else{
           $('#otp_v_submit').prop('disabled', false);
          $('#forgot_otp_error').text('');
         }
     });

   $('#verify_otp_value').keyup(function (){
       var otp=$('#verify_otp_value').val();
       if(isNaN(otp)){
        if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
       }else{
        $('#verify_otp_value').text('');
      } 

         if(otp.length > 4){
           $('#verify').prop('disabled', true);
           $('#verify_reg_error').text('OTP must not exceed 4 digit');
         }else if(otp.length < 4 ){
           $('#verify').prop('disabled', true);
           $('#verify_reg_error').text('OTP must be of 4 digit');
         }else{
           $('#verify').prop('disabled', false);
          $('#verify_reg_error').text('');
         }
     });


   
   //added code for validation of forget_mobile only number end 
   //added code for register mobile field validation beg


  $('#reg_mobile_number').keyup(function (){

    var mobile=$('#reg_mobile_number').val();
    //alert(mobile);
    if(isNaN(mobile)){
      $('#reg_no_error').text('Only Numbers are allowed');
      
       if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
      
    }else{
      $('#reg_no_error').text('');
    }

    if(mobile.length > 10){
       $('#register-submit').prop('disabled', true);
       $('#reg_no_error').text('Mobile no. must not exceed 10 digit');
    }else if(mobile.length < 10 ){
         $('#register-submit').prop('disabled', true);
          $('#reg_no_error').text('Mobile no. must be of 10 digit');
    }else{
         $('#register-submit').prop('disabled', false);
         $('#reg_no_error').text('');
    }
   

   });

  $('#reg_email_address').keyup(function (){

     var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var email = $('#reg_email_address').val();
        
        if(email !== '') {
          if(pattern.test(email)) {
              $('#reg_email_address_error').text('');
              $('#register-submit').prop('disabled', false);
           }else{
              $('#reg_email_address_error').text('Please enter valid email');
              $('#register-submit').prop('disabled', true);
           }
        } 

   });




   //added code for register mobil field validation end
   //to check password length not less than 6 beg
   $('#login_pass').keyup(function (){
    var login_pass=  $('#login_pass').val();
    if(login_pass.length < 6){
      $('#pass_error').text('Password should be of minimum 6 Characters');
       $('#login-submit').prop('disabled', true);
    }else{
      $('#pass_error').text('');
       $('#login-submit').prop('disabled', false);
    }
   });

  //added code to validation reg pass beg
  $('#password').blur(function (){
    var password=  $('#password').val();
   // alert('jkvj')
    if(password.length < 6){
      $('#reg_error').text('Password should be of minimum 6 Characters');
       $('#register-submit').prop('disabled', true);
    }else{
      $('#reg_error').text('');
       $('#register-submit').prop('disabled', false);
    }
   });
  //added code to validation reg pass end

   //to check password length not less than 6 end
   //added code for registeration validation beg
   /* $('#registered_number').keyup(function (){

    var registered_number=$('#mobilregistered_number').val();
    //alert(mobile);
    if(isNaN(registered_number)){
      //$('#reg_no_error').text('Only Numbers are allowed');
      //$('#register-submit').prop('disabled', true);
       if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
      
    }else{
      $('#reg_no_error').text('');
      $('#register-submit').prop('disabled', false);
    }
   

   });*/
   //added code for registeration validation end
   //reg pass validation beg reg_pass
    $('#reg_pass').keyup(function (){
      //alert('jkfjbk');
    var reg_pass=  $('#reg_pass').val();
    if(reg_pass.length < 6){
      $('#reg_error').text('Password should be of minimum 6 Characters');
       $('#register-submit').prop('disabled', true);
    }else{
      $('#reg_error').text('');
       $('#register-submit').prop('disabled', false);
    }
   });
   //reg pass validation end
});
//confirm pass reg beg
$('#c_password').keyup(function(){
    var reg_pass=  $('#reg_pass').val();
    var reg_confirm_pass=  $('#c_password').val();
  
    if( reg_confirm_pass == reg_pass){
      //alert('yes');
    $('#reg_confirm_error').text('');
      
      $('#register-submit').prop('disabled', false);
    }else{  
      $('#reg_confirm_error').text('Password Mismatch');
      $('#register-submit').prop('disabled', true);
    }
});

//Forgot password validate
$('#user_new_pass').keyup(function (){
      //alert('jkfjbk');
    var reg_pass=  $('#user_new_pass').val();
    if(reg_pass.length < 6){
      $('#forgot_new_password_error').text('Password should be of minimum 6 Characters');
       $('#forgot-submit').prop('disabled', true);
    }else{
      $('#forgot_new_password_error').text('');
       $('#forgot-submit').prop('disabled', false);
    }
   });
   //reg pass validation end
//Forgot confirm pass reg beg
$('#user_new_cpass').keyup(function(){
    var reg_pass=  $('#user_new_pass').val();
    var reg_confirm_pass=  $('#user_new_cpass').val();
  
    if( reg_confirm_pass == reg_pass){
      //alert('yes');
    $('#forgot_conform_password_error').text('');
      
      $('#forgot-submit').prop('disabled', false);
    }else{  
      $('#forgot_conform_password_error').text('Password Mismatch');
      $('#forgot-submit').prop('disabled', true);
    }
});

//confirm pass reg end
//ajax code to submit form beg
$('#forgot-password-form').on('submit',function(event){
        event.preventDefault();

        user_mobile = $('#forget_mobile').val();
        

        $.ajax({
          url: "{{url('/forgot-password')}}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            user_mobile:user_mobile,
           
          },
          success:function(response){
            console.log(response);
            if(response.status == 1){
             // $('#verify_otp').show();
             // $('#forgetpassword').hide();
             // $('#verify_mobile').val(response.data[0].user_phone); //to place mobile number in verify otp form
              $('.password_sent').text(response.message);
              //added on 11 nov beg setpass_msg
              $('#forgetpassword').hide();
             // $('#setpassword').show();
              $('#verify_pass_otp').show();
              $('#register_otp').html('OTP - '+response.otp);
              $('.forget_otp_veify').text(response.message);
             
               //added on 11 nov end

            }
            if(response.status == 0){
             // $('#verify_otp').show();
             // $('#forgetpassword').hide();
             // $('#verify_mobile').val(response.data[0].user_phone); //to place mobile number in verify otp form
              $('.password_sent_error').text(response.message);
              $('#register_otp').html('OTP - '+response.otp);

            }
          },
         });
        }); 
//ajax code to submit form end
//addded code to verify otp beg
$('#verify_otp_form').on('submit',function(event){
        event.preventDefault();

       //var user_mobile = $('#verify_mobile').val();
       var user_mobile = $('#reg_mobile_number').val();
        
       var verify_otp_value = $('#verify_otp_value').val();
        
       //alert(user_mobile);
      // alert(verify_otp_value);
        $.ajax({
          url: "{{url('/verify-otp')}}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            user_mobile:user_mobile,
            otp:verify_otp_value
           
          },
          success:function(response){
            console.log(response);
            if(response.status == 1){
              // $('#verify_otp').show();
              $('.otp_match_or_not').css('color','green'); 
              $('.otp_match_or_not').text(response.message); 
              //swal after successful registeration beg
              swal("Thanks!", "You have registered success!", "success");
              //swal after successful registeration end
              window.location.href="{{url('/my-profile')}}";

              // $('#forgetpassword').hide();
              // $('#verify_mobile').val(response.data.user_phone); //to place mobile number in verify otp form

            } else if(response.status == 0){
              $('.otp_match_or_not').text(response.message); 
              $('.otp_match_or_not').css('color','red'); 
            }
          },
         });
        }); 
//addded code to verify otp end


//added code to verify otp for pass beg 8 nov
$('#forget-otp-form').on('submit',function(event){
        event.preventDefault();

       //var user_mobile = $('#verify_mobile').val();
       var user_mobile = $('#forget_mobile').val();
        //alert(user_mobile);
       var verify_otp_value = $('#pass_v_otp').val();
        
       //alert(user_mobile);
      // alert(verify_otp_value);
        $.ajax({
          url: "{{url('/forget_otp_veify')}}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            user_mobile:user_mobile,
            otp:verify_otp_value
           
          },
          success:function(response){
            console.log(response);
            if(response.status == 1){
              $('#verify_pass_otp').hide();
               $('#setpassword').show();
               
             // $('.otp_match_or_not').css('color','green'); 
             // $('.otp_match_or_not').text(response.message); 
              //window.location.href="{{url('/my-profile')}}";

              // $('#forgetpassword').hide();
              // $('#verify_mobile').val(response.data.user_phone); //to place mobile number in verify otp form

            } else if(response.status == 0){
              $('.forget_otp_veify').text(response.message); 
              $('.forget_otp_veify').css('color','red'); 
            }
          },
         });
        });

//added code to verify otp for pass end


//ajax code for register only beg /login-register
$('#register-form').on('submit',function(event){
        event.preventDefault();

       
        var full_name = $('#full_name').val();
        var mobile_number = $('#reg_mobile_number').val();
        var password = $('#reg_pass').val();
        var c_password = $('#c_password').val();
        var user_email = $('#reg_email_address').val();
        console.log(user_email);
        
        var type='register';
        

        $.ajax({
          url: "{{url('/login-register')}}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            full_name:full_name,
            mobile_number:mobile_number,
            password:password,
            c_password:c_password,
            user_email:user_email,
            type:type,
           
          },
          success:function(response){
            console.log(response); 
            if(response.status == 1){
             // $('#verify_otp').show();
             // $('#forgetpassword').hide();
             // $('#verify_mobile').val(response.data[0].user_phone); //to place mobile number in verify otp form
             //alert(response.otpval);

             //Change by Anish [04-06]
              /*$('.password_sent').text(response.message);
              $('#register_verify_otp').html('OTP - '+response.otpval);
              $('#verify_otp').show();
              $('#register-form').hide();*/

              swal("Thanks!", "You have registered success!", "success");
              var pathname = window.location.pathname
              console.log(pathname);
              if(pathname == '/checkout'){
                window.location.href="{{url('/checkout')}}";
              }else{
                window.location.href="{{url('/my-profile')}}";
              }
              //Change end by Anish

            }
            if(response.status == 0){
             // $('#verify_otp').show();
             // $('#forgetpassword').hide();
              $('#register-msg').text(response.message);
              $('#register_verify_otp').html('OTP - '+response.otpval);
             // $('#verify_mobile').val(response.data[0].user_phone); //to place mobile number in verify otp form
              $('.password_sent').text(response.message);

            }
          },
          error:function(error){
            console.log(error);

          }
         });
        }); 
//ajax code for register only end
//added code for login form beg
$('#login-form').on('submit',function(event){
       console.log(event.target.validity);
       //console.log(event.target.validity.valid);
        event.preventDefault();

       
       
        var mobile_number = $('#mobile_number').val();
        var password = $('#login_pass').val();
         var type= 'login';
        

        $.ajax({
          url: "{{url('/login-register')}}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            mobile_number:mobile_number,
            password:password,
            type:type,
           
          },
          success:function(response){
            console.log(response); 
            if(response.status == 1){
             // $('#verify_otp').show();
             // $('#forgetpassword').hide();
             // $('#verify_mobile').val(response.data[0].user_phone); //to place mobile number in verify otp form
             // $('.password_sent').text(response.message);
              //$('#verify_otp').show();
             // $('#register-form').hide();

             //console.log(response); //original
            var pathname = window.location.pathname
            // console.log(window.location.pathname);
            console.log(pathname);
            //added code to check current route beg
            //added code to check current route end
            if(pathname == '/checkout'){
            
               window.location.href="{{url('/checkout')}}";
            }else{
               window.location.href="{{url('/my-profile')}}";
            }

              

            }
            if(response.status == 0){
              // alert(response.message);
             $('#login-msg').text(response.message);
            
             // $('#forgetpassword').hide();
             // $('#verify_mobile').val(response.data[0].user_phone); //to place mobile number in verify otp form
              //$('.password_sent').text(response.message);
              console.log(response);

            }
          },
         });
        });
//added code for login form end
//added code to set new pass beg 8 nov
$('#set-password-form').on('submit',function(event){
        event.preventDefault();

       //var user_mobile = $('#verify_mobile').val();
       var user_mobile = $('#forget_mobile').val();
        //alert(user_mobile);
       var verify_otp_value = $('#pass_v_otp').val();
       var user_new_pass = $('#user_new_pass').val();
       var user_new_cpass = $('#user_new_cpass').val();
        
       //alert(user_mobile);
       //alert(verify_otp_value);
       if(user_new_cpass == user_new_pass){
            $.ajax({
              url: "{{url('/new-password')}}",
              type:"POST",
              data:{
                "_token": "{{ csrf_token() }}",
                user_mobile:user_mobile,
                verify_otp_value:verify_otp_value,
                user_new_pass:user_new_pass,
                user_new_cpass:user_new_cpass,
               
              },
              success:function(response){
                console.log(response);
                if(response.status == 1){
                  //alert(response.status);
                  
                    //$('.setpass_msg').text(response.message); 
                    //hide form after set new password
                    $('#set-password-form').hide(); 
                     // $('#loginbtn').click(); // new code 13 nov
                    //show login form
                    sweetAlert('Congratulations!', 'Your New password has been successfully updated', 'success');
                    //
                    $('#login').show(); //old code
                  
                   // $('#register-form').hide(); //old code
                   //aded fix error of both login n reg form show beg
                 /* $('#reg_tab').click(function(){
                  $('#login').hide();
                  $('#register').show();

                  });
                   $('#loginbtn').click(function(){
                  $('#register').hide();
                  $('#login').show();
                  });*/
                   //aded fix error of both login n reg form show end
                    

                 

                } else if(response.status == 0){
                  // alert(response.status);
                  $('.setpass_msg').text(response.message); 
                  $('.setpass_msg').css('color','red'); 
                }
              },
             });
      } else{
        $('.setpass_msg').text('Password Mismatch');
        $('.setpass_msg').css('color','red');
      }
        });
      

//added code to set new pass end 8 nov
//register login show hide 19 nov beg
$('#reg_tab').click(function(){
   //alert('hi')
$('#login').hide();
$('#register').show();
 $('#register-form').show();
 $('#verify_otp').hide(); //to hide otp
 //added code to hide verigy otp form beg
 $('#verify_pass_otp').hide();
 //added code to hide verigy otp form end

});
 $('#loginbtn').click(function(){
$('#register').hide();
$('#register-form').hide();
$('#login').show();
//added code to hide verigy otp form beg
 $('#verify_pass_otp').hide();
 //added code to hide verigy otp form end
});
//register login show hide 19 nov end

//added code to resend otp if user not get otp 1st time beg
function resendotp(){
   var user_mobile = $('#reg_mobile_number').val();
   //ajax to resend otp again on user mobile beg
      $.ajax({
          url: "{{url('/resend-otp')}}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            user_mobile:user_mobile
           
           
          },
          success:function(response){
            console.log(response);
            if(response.status == 1){
              $('.otp_resend_or_not').text(response.message);
              $('.otp_resend_or_not').css('color','green');
              $('#register_verify_otp').html('OTP - '+response.otp);
             
            } else if(response.status == 0){
              $('.otp_resend_or_not').text(response.message);
              $('.otp_resend_or_not').css('color','red');
              $('#register_verify_otp').html('OTP - '+response.otp);
             
            }
          },
         });
   //ajax to resend otp again on user mobile end
}
//added code to resend otp if user not get otp 1st time end
//to manage forget tab beg
$('#forgettab').click(function(){

   $('#forgetpassword').show();
   $('#login').hide();
});

$('#loginbtn').click(function(){

   $('#forgetpassword').hide();
   $('#login').show();
});
$('#reg_tab').click(function(){

   $('#forgetpassword').hide();
   $('#register').show();
});
//to manage forget tab end
////////////resend otp forget pass beg//////////////
//resend otp forget pass beg resendotpforgetpass 
function resendotpforgetpass(){
   var user_mobile = $('#forget_mobile').val();
   //ajax to resend otp again on user mobile beg
      $.ajax({
          url: "{{url('/resend-otp-forget-pass')}}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            user_mobile:user_mobile
           
           
          },
          success:function(response){
            console.log(response);
            if(response.status == 1){
              $('.forget_otp_veify').text(response.message);
              $('.forget_otp_veify').css('color','green');
              $('#register_otp').html('OTP - '+response.otp);
             
            } else if(response.status == 0){
              $('.forget_otp_veify').text(response.message);
              $('.forget_otp_veify').css('color','red');
              $('#register_otp').html('OTP - '+response.otp);
             
            }
          },
         });
   //ajax to resend otp again on user mobile end
}
//resend forget pass end
////////////resend otp forget pass end//////////////

</script>
<script type="text/javascript">
  $(function(){$(".checkbox_class").change(function(){var x=this.checked;$(".checkbox_class").prop("checked",x);});});

</script>
