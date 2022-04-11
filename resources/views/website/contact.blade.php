@extends('website.layouts.app')



@section('content')
<style>
   
   .collapse_ext {position: fixed;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 50%;
z-index:99999;
display:block;
}
input.razorpay-payment-button{
   border: unset;background: #e96125 none repeat scroll 0 0 !important;color: #fff;padding: 5px !important;border-radius: 2px !important; display:none;
}
</style>
<?php 
$username='';
$useremail='';
$userphone='';


?>

@if(session()->has('userData'))
<?php
$userdata =session()->get('userData'); 
$username=$userdata->user_name;
$useremail=$userdata->user_email;
$userphone=$userdata->user_phone;


 ?>
@endif
<!-- Inner Header -->
      <section class="section-padding bg-dark inner-header">
        @if(session()->has('message'))
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script type="text/javascript">
          swal("Query", "{{ session()->get('message') }}", "success");
          /*Email Sent*/
        </script>
   
        @endif
         <div class="container">
            <div class="row">
               <div class="col-md-12 text-center">
                  <h1 class="mt-0 mb-3 text-white">Contact Us</h1>
                  <div class="breadcrumbs">
                     <p class="mb-0 text-white"><a class="text-white" href="index.php">Home</a>  /  <span class="">Contact Us</span></p>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Inner Header -->
      <!-- Contact Us -->
      <section class="section-padding">
         <div class="container">
            <div class="row">
               
               {{-- get dynamic content of contact --}}
               {!! $content->description !!}
            </div>
         </div>
      </section>
      <!-- End Contact Us -->
      <!-- Contact Me -->
      <section class="section-padding  bg-white">
         <div class="container">
            <div class="row">
               <div class="col-lg-12 col-md-12 section-title text-left mb-4">
                  <h2>Contact Us</h2>
               </div>
               <form class="col-lg-12 col-md-12"  method="post" action="{{url('/contact-us')}}" name="">
                {{csrf_field()}}
                  <div class="control-group form-group">
                     <div class="controls">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <input type="text" placeholder="Full Name" class="form-control" id="contact_form_name" name="fullname" maxlength="30" onkeypress="textonly(event)" required />
                        <span id="contact_form_name_error" style="color:red;position: absolute;margin-top:-5px;right:25px;"></span>
                        <p class="help-block"></p>
                     </div>
                  </div>
                  <div class="row">
                     <div class="control-group form-group col-md-6">
                        <label>Phone Number <span class="text-danger">*</span></label>
                        <div class="controls">
                           <input type="text"  name="phone" placeholder="Phone Number"  class="form-control" id="contact_form_phone" maxlength="10" onkeypress="numonly(event)" required>
                           <span id="contact_form_phone_error" style="color:red;position: absolute;margin-top:-5px;right:25px;"></span>
                        </div>
                     </div>
                     <div class="control-group form-group col-md-6">
                        <div class="controls">
                           <label>Email Address <span class="text-danger">*</span></label>
                           <input type="email" placeholder="Email Address"  class="form-control" id="contact_form_email" name="email" required="">
                           <span id="contact_form_email_error" style="color:red;position: absolute;margin-top:-5px;right:25px;"></span>
                        </div>
                     </div>
                  </div>
                  <div class="control-group form-group">
                     <div class="controls">
                        <label>Message <span class="text-danger">*</span></label>
                        <textarea rows="4" cols="100" placeholder="Message"  class="form-control" id="message"  maxlength="999" style="resize:none" name="message" required=""></textarea>
                     </div>
                  </div>
                  <div id="success"></div>
                  <!-- For success/fail messages -->
                 <!--  <button type="submit" class="btn btn-secondary">Send Message</button> -->
                  <input type="submit" id="contactForm" name="submit" value="Send Message" class="btn btn-secondary" />
               </form>
            </div>
         </div>
      </section>
      <!-- End Contact Me -->


<script>

   
 




 function removeproduct(rpid){

   

   //alert(rpid);



   //added code to remove product beg pp

   /*var totalitems=$('.cart-value').text();

   var totalitems= parseInt(totalitems) + 1;

   $('.cart-value').text(totalitems);*/

   var rpidd=rpid;

   var ramount= $('#pp'+rpidd).text();

   //alert(ramount);  s

   var tp= $('#tp1').text();
   //alert(parseInt(tp));
   

   //alert(tp);

   var newtotal= parseInt(tp) - parseInt(ramount);

    $('.tp').text(parseInt(newtotal));

     var cartcurrent=$('.cart-value').text();

     //alert();

     $('.cart-value').text(parseInt(cartcurrent) - 1);



   //alert(pidd);

   $.ajax({



          type: 'get',



         // url: 'http://localhost/blog/public/addtocart',remove-product

           url:"{{url('/remove-product')}}/"+rpidd,



          //data: {'pid':pidd},  



          success : function(data){



            // $('#category'+value).remove()

             $('#rp'+rpidd).remove();
             var tp= $('#tp1').text();
             if(parseInt(tp) == 0 ){
              //alert('yes');
            $('.cart-table').remove();
            location.reload(true); //reload

            }



             console.log(data);

             //alert(data);

            // alert('Product has been removed to cart');



          }



        });

   //added code to remove product end



 }



 //added code to manage inc/dec qty beg

 function manageqty(id,type){

   //alert(type);
   var quant=$('#quant'+id).val();
   //alert(quant);


   var pidd=id;

   var type=type;

  var prevqty=$('#quant'+id).val();

   //alert(prevqty);

   if(type == 'inc') {

  var quant= $('#quant'+id).val(parseInt(prevqty) + 1);

 var getquant=$('#quant'+id).val();

  var uprice = $('#uprice'+id).text();
  //alert(uprice);

  //alert(quant);

  var newsubtotal= parseFloat(getquant) * parseFloat(uprice);

 // alert(parseInt(newsubtotal))

  var subtotal = $('#pp'+id).text(newsubtotal);

   var prevtotal=$('#tp1').text();

   //alert(parseInt(prevtotal));

$('.tp').text(parseFloat(parseFloat(prevtotal) + parseFloat(uprice)));

}

 if(type == 'dec' && quant >= 2) {

  var quant= $('#quant'+id).val(parseInt(prevqty) - 1);

  var getquant=$('#quant'+id).val();

  var uprice = $('#uprice'+id).text();

  //alert(quant);

  var newsubtotal= parseFloat(getquant) * parseFloat(uprice);

  //alert(parseInt(newsubtotal));

  var subtotal = $('#pp'+id).text(newsubtotal);

  var prevtotal=$('#tp1').text();

$('.tp').text(parseFloat(parseFloat(prevtotal) - parseFloat(uprice)));

}



   /////////////////////////////////inc dec beg

   $.ajax({



          type: 'get',



         // url: 'http://localhost/blog/public/addtocart',

           url:"{{url('/addtocart')}}/"+pidd+'/'+type,



          //data: {'pid':pidd},  



          success : function(data){



            // $('#category'+value).remove()



             //console.log(data);

             //alert(data);

             //alert('Product qty has been increased cart');



          }



        });



   ///////////////////////////////////// inc dec end

 }

 //added code to manage inc/dec qty end
 //aadded code for onchnag payment option beg
 function payoption(){
   //alert('gh');
   var payment_type = $( "#paymenttype option:selected" ).val();
   //alert(payment_type);
   if(payment_type == 0){
      //alert(payment_type);
     
   // $('#cod').show();
   //$('input.razorpay-payment-button').hide();
   }
   if(payment_type == 1){
      // alert(payment_type);
  // $('#cod').hide();
   //$('input.razorpay-payment-button').show();
   $('#checkout_form').attr('action', "payment-inititate-request")

   }
 }
 //aadded code for onchnag payment option end

 //

 

   </script>


   @endsection
