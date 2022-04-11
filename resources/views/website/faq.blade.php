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
<section class="faq-page section-padding">
   @if(session()->has('message'))
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script type="text/javascript">
          swal("Email Sent", "{{ session()->get('message') }}", "success");
          
        </script>
   
        @endif
         <div class="container">
            <div class="row">
               <div class="col-lg-10 mx-auto">
                  <div class="row">
                     <div class="col-lg-6 col-md-6">
                        
                        {{-- added code to make faq dynamic end--}}
                       
                        {!! $content->description !!}
                        
                     </div>
                     <div class="col-lg-6 col-md-6">
                        <div class="card card-body">
                           <div class="section-header">
                              <h5 class="heading-design-h5">
                                 Ask us question
                              </h5>
                           </div>
                           <form method="post" action="{{url('/contact-us')}}">
                            {{csrf_field()}}
                              <div class="row">
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label class="control-label">Your Name <span class="required">*</span></label>
                                       <input class="form-control border-form-control"  placeholder="Enter Name" type="text" name="fullname" onkeypress="textonly(event)" maxlength="30" required />
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label">Email Address <span class="required">*</span></label>
                                       <input class="form-control border-form-control "  placeholder="ex@gmail.com" type="email" name="email" required>
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                       <label class="control-label">Phone <span class="required">*</span></label>
                                       <input class="form-control border-form-control"  placeholder="Enter Phone" type="text" name="phone" onkeypress="numonly(event)" maxlength="10" required />
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label class="control-label">Your Message <span class="required">*</span></label>
                                       <textarea class="form-control border-form-control" name="message" required></textarea >
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-sm-12 text-right">
                                   <!--  <button type="button" class="btn btn-danger btn-lg"> Cencel </button> -->
                                    <!-- <button type="button" class="btn btn-success btn-lg"> Send Message </button> -->
                                    <input type="submit" name="submit" value="Send Message" class="btn btn-success btn-lg">  
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <section class="section-padding bg-white border-top" style="display:none;">
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-truck-fast"></i>
                     <h6>Free & Next Day Delivery</h6>
                     <p>Lorem ipsum dolor sit amet, cons...</p>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-basket"></i>
                     <h6>100% Satisfaction Guarantee</h6>
                     <p>Rorem Ipsum Dolor sit amet, cons...</p>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-6">
                  <div class="feature-box">
                     <i class="mdi mdi-tag-heart"></i>
                     <h6>Great Daily Deals Discount</h6>
                     <p>Sorem Ipsum Dolor sit amet, Cons...</p>
                  </div>
               </div>
            </div>
         </div>
      </section>


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