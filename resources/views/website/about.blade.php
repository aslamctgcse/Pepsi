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
         <div class="container">
            <div class="row">
               <div class="col-md-12 text-center">
                  <h1 class="mt-0 mb-3 text-white">About Us</h1>
                  <div class="breadcrumbs">
                     <p class="mb-0 text-white"><a class="text-white" href="{{url('/')}}">Home</a>  /  <span class="">About Us</span></p>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Inner Header -->
      <!-- About -->
      <section class="section-padding bg-white">
         <div class="container">
            <div class="row">
               {{-- about page content --}}
               {!! $aboutContent->description !!}
            </div>
         </div>
      </section>
      <!-- End About -->
      <!-- What We Provide -->
      <section class="section-padding" style="display: none;">
         <div class="section-title text-center mb-5">
            <h2>What We Provide?</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
         </div>
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-md-4">
                  <div class="mt-4 mb-4"><i class="text-success mdi mdi-shopping mdi-48px"></i></div>
                  <h5 class="mt-3 mb-3 text-secondary">Best Prices & Offers</h5>
                  <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
               </div>
               <div class="col-lg-4 col-md-4">
                  <div class="mt-4 mb-4"><i class="text-success mdi mdi-earth mdi-48px"></i></div>
                  <h5 class="mb-3 text-secondary">Wide Assortment</h5>
                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text eve.</p>
               </div>
               <div class="col-lg-4 col-md-4">
                  <div class="mt-4 mb-4"><i class="text-success mdi mdi-refresh mdi-48px"></i></div>
                  <h5 class="mt-3 mb-3 text-secondary">Easy Returns</h5>
                  <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using.</p>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-4 col-md-4">
                  <div class="mt-4 mb-4"><i class="text-success mdi mdi-truck-fast mdi-48px"></i></div>
                  <h5 class="mb-3 text-secondary">Free & Next Day Delivery</h5>
                  <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC.</p>
               </div>
               <div class="col-lg-4 col-md-4">
                  <div class="mt-4 mb-4"><i class="text-success mdi mdi-basket mdi-48px"></i></div>
                  <h5 class="mt-3 mb-3 text-secondary">100% Satisfaction Guarantee</h5>
                  <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour.</p>
               </div>
               <div class="col-lg-4 col-md-4">
                  <div class="mt-4 mb-4"><i class="text-success mdi mdi mdi-tag-heart mdi-48px"></i></div>
                  <h5 class="mt-3 mb-3 text-secondary">Great Daily Deals Discount</h5>
                  <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using.</p>
               </div>
            </div>
         </div>
      </section>
      <!-- End What We Provide -->
      <!-- Our Team -->
      <section class="section-padding bg-white" style="display: none;">
         <div class="section-title text-center mb-5">
            <h2>Our Team</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
         </div>
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-md-4">
                  <div class="team-card text-center">
                     <img class="img-fluid mb-4" src="img/user/1.jpg" alt="">
                     <p class="mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been.</p>
                     <h6 class="mb-0 text-success">- Stave Martin</h6>
                     <small>Manager</small>
                  </div>
               </div>
               <div class="col-lg-4 col-md-4">
                  <div class="team-card text-center">
                     <img class="img-fluid mb-4" src="img/user/2.jpg" alt="">
                     <p class="mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been.</p>
                     <h6 class="mb-0 text-success">- Mark Smith</h6>
                     <small>Designer</small>
                  </div>
               </div>
               <div class="col-lg-4 col-md-4">
                  <div class="team-card text-center">
                     <img class="img-fluid mb-4" src="img/user/3.jpg" alt="">
                     <p class="mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been.</p>
                     <h6 class="mb-0 text-success">- Ryan Printz</h6>
                     <small>Marketing</small>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- End Our Team -->
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