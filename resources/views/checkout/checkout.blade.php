@extends('website.layouts.app')



@section('content')
<style>
  
  #showOnlineAmountBlock{
    margin-bottom: 10px;
    /*text-align: right;*/
    font-weight: 600;
    color: #000;
  }

 /* #OnlineDisView,#OnlineTotalAmountView{
        min-width: 84px;
        display: inline-block;
  }*/
  .noteText{
    color: green;
    text-decoration: underline;
  }

 .deliverychargeContainer,.coupon_details,.bulk_details,.cartdiv
 {
  height: 50px;
  
  border-bottom: 1px solid #eeeeee;
  
}
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
.otsidebar,.deliveryamount{
 width: 50%;
 float: left;
 font-weight: bold;
 text-align: right;
 color: #000;
 font-size: 20px;
}
.cartdiv,.coupon_details,.cartdiv,.bulk_details,.deliverychargeContainer,.cartdivgrand {
  padding: 10px;
}
.coupon_container {
  margin-top: 20px;
  background: #fff;
  min-height: 124px;
  padding: 20px;
}
.coupon_container .row{
  margin-right: 0px;
  margin-left: 0px; 
}
.coupon_details,.bulk_details {
  height: 50px;
}
.coupon_container .couponViewbtn{
      margin-top: 10px;
}


/*Css for sweet alert text center*/
.swal-text{
  text-align: center;
  line-height: 23px;
}
.select2-search--dropdown {
    display: none!important;
}
.availCoupons{
    padding-top: 8px;
}
.couponModal .modal-content{
  border-radius: 4px;
}
.couponModal .modal-header{
  padding-top: 8px;
  padding-bottom: 8px;

}
.couponModal .modal-title{
  width: 100%;
  padding-left: 29px;
  text-align: center;
  font-size: 18px;
}
.bodyCss{
  text-align: center;
  padding-top: 0px;
  padding-bottom: 0px;
}
.bodyCss p{
  line-height: 17px;
  color:#000;
  margin-bottom: 4px;
}
.bodyCss .col{
  border: 1px solid #dedede;
  padding: 5px;
}
.bodyCss .bodyHeader{
  font-weight: 600;
}
.noDataMsg{
  font-size: 16px;
  padding: 20px;
}
#timeslotSection{
  /*min-height: 50px;*/
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
{{-- added code if wallet amount is insufficient beg --}}

{{-- added code if wallet amount is insufficient end --}}
@if(session()->has('msg'))
<div class="card collapse_ext">
  <div id="collapsefours " class="collapses" aria-labelledby="headingThree" data-parent="#accordionExample">

    <div class="card-body">

     <div class="text-center">

      <div class="col-lg-10 col-md-10 mx-auto order-done">

       <i class="mdi mdi-check-circle-outline text-secondary"></i>

       <h4 class="text-success">Congrats! Your Order has been Accepted..</h4>

       <p>

      </p>

    </div>

    <div class="text-center">

     <a href="{{url('/')}}"><button type="submit" class="btn btn-secondary mb-2 btn-lg">Return to store</button></a>

   </div>

 </div>

</div>

</div>
</div>
@endif


<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
  @if(session()->has('message'))

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript">
    swal( '{{ session()->get('message') }}');
  </script>
  @endif

  <div class="container">

    <div class="row">

     <div class="col-md-12">

      <a href="{{url('/')}}"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span> <a href="{{url('/checkout')}}">Checkout</a>

    </div>

  </div>

</div>

</section>

<section class="checkout-page section-padding">

 <div class="container">

  <div class="row">

   <div class="col-md-8">

    <div class="checkout-step">

     <div class="accordion" id="accordionExample">

      

      <div class="card checkout-step-two">

       <div class="card-header" id="headingTwo">

        <h5 class="mb-0">

         <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">

           <span class="number">1</span> Delivery Address

         </button>

       </h5>

     </div>

     <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">

      <div class="card-body">

        <form method="post" action="{{url('/checkout')}}" id="checkout_form">
          {{csrf_field()}}

          <div class="row">

           <div class="col-sm-6">

            <div class="form-group">

             <label class="control-label">Full Name <span class="required">*</span></label>
             <?php 

             $ordertotals=0;
             $ordertotalMrp=0;
             $bulkOrderDiscount=0;

             $totalqty=0;
             $storeTotalDetail='';
             if(!empty($products)) {
              foreach($products as $product){

                                //dd($product);
                              //$price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
                $price_after_discount=$product->price;
                              //$discountprice=$product->mrp*$product->discount_percentage/100;
                $discountdat=(($product->mrp - $product->price)/$product->mrp)* 100;
                $ordertotals+=$price_after_discount * $qty[$product->varient_id];
                $ordertotalMrp+=$product->mrp * $qty[$product->varient_id]; 

              }
              $checkStore=array();
              $checka1='';
              $checka2=0;
              $secondCount = 0;
              foreach($products as $key => $value){
                $checka1 = $products[$key]->product_store_id;
                if($checka2!=$checka1){
                  $products[$key]->totalPrice =  $products[$key]->price*$qty[$products[$key]->varient_id];
                  $products[$key]->totalMrp = $products[$key]->mrp*$qty[$products[$key]->varient_id];
                  $checkStore[] = $products[$key];
                  $checka2=$checka1;
                  $secondCount++;        
                }else{
                  $checkStore[$secondCount-1]->totalPrice +=   $products[$key]->price*$qty[$products[$key]->varient_id];
                  $checkStore[$secondCount-1]->totalMrp +=   $products[$key]->mrp*$qty[$products[$key]->varient_id];;
                }
              }
              $newCharge=0;
              foreach($checkStore as $key => $value){
                $newDelcharge=DB::table('freedeliverycart_by_store')
                ->where('delivery_store_id',$checkStore[$key]->product_store_id) 
                ->where('min_cart_value','>=',$checkStore[$key]->totalPrice)
                ->first();
                if($newDelcharge){
                  $kk = $newDelcharge->del_charge;
                  $checkStore[$key]->del_charge=$kk;
                }else{
                  $kk = 0;
                  $checkStore[$key]->del_charge=$kk;
                }

                $newBulkDiscount=DB::table('bulk_order_discount')
                //->where('bulk_order_store_id',$checkStore[$key]->product_store_id) 
                ->where('bulk_order_min_amount','<=',$checkStore[$key]->totalPrice)
                ->where('bulk_order_max_amount','>=',$checkStore[$key]->totalPrice)
                ->first();
                //dd($newBulkDiscount); 

                if($newBulkDiscount){
                  if($newBulkDiscount->bulk_order_discount_type=='percentage'){
                    $bb = $checkStore[$key]->totalPrice*$newBulkDiscount->bulk_order_discount/100;  
                  }else{
                    $bb =$newBulkDiscount->bulk_order_discount;
                  }  
                }else{
                  $maxValueAmountLimit = DB::table('bulk_order_discount')->max('bulk_order_max_amount');
                  $maxBDiscountData = DB::table('bulk_order_discount')
                  ->where('bulk_order_max_amount',$maxValueAmountLimit)
                  ->first();
                  if($checkStore[$key]->totalPrice>$maxValueAmountLimit){
                    if($maxBDiscountData->bulk_order_discount_type=='percentage'){
                      $bb = $checkStore[$key]->totalPrice*$maxBDiscountData->bulk_order_discount/100;  
                    }else{
                      $bb =$maxBDiscountData->bulk_order_discount;
                    }
                  }else{
                    $bb = 0;
                  }
                }

                $bulkOrderDiscount+= $bb;
              }
              //dd($bulkOrderDiscount);
              if($bulkOrderDiscount>0){
                $viewBulk = 'block';
              }else{
                $viewBulk = 'none';
              }
              if(!empty($checkStore)){
                //dd($checkStore);
                $storeTotalDetail=json_encode($checkStore);
              }
            }
            ?>

            <input class="form-control border-form-control" name="ufname" value="{{$username}}" placeholder="" type="text" id="ufname" required>
            {{-- delivery charge end --}}
            @php     $charge=0  @endphp
            @if(session()->has('delivercharge'))
            
            @php     $charge=session()->get('delivercharge')  @endphp
            
            @endif
            @if(session()->has('bulkOrderDiscount'))
            
            @php     $bulkOrderDiscount=session()->get('bulkOrderDiscount')  @endphp
            
            @endif

            @if($ordertotals == 0)
            @php $charge=0;$bulkOrderDiscount=0; @endphp
            @endif
            {{-- added code for order total  beg--}}
            <input type="hidden" name="bulkOrderDiscount" value="{{$bulkOrderDiscount}}" id="bulkOrderDiscount"/>
            <input type="hidden" name="storeWiseTotal" value="{{$storeTotalDetail}}" id="storeWiseDetail"/>
            <input type="hidden" name="ordertotal" value="{{$ordertotals}}" id="ot"/>
            <input type="hidden" name="ordertotalmrp" value="{{$ordertotalMrp}}" id="otm"/>
            <input type="hidden" name="delcharge" id="delcharge" value="{{$charge}}" id="ot"/>

          </div>

        </div>

        <!-- <div class="col-sm-6">

          <div class="form-group">

           <label class="control-label">Last Name <span class="required">*</span></label>

           <input class="form-control border-form-control"  name="ulname" value="" placeholder="" type="text" id="ulname" required>

         </div>

       </div> -->
       <input class="form-control border-form-control"  name="ulname" value="" placeholder="" type="hidden" id="ulname">
       <div class="col-sm-6">

        <div class="form-group">

         <label class="control-label">Phone <span class="required">*</span></label>

          <input class="form-control border-form-control"  name="uphone" value="{{$userphone}}" placeholder=""  maxlength="10" type="text" id="uphone" required>

        </div>

      </div>

     </div>

     <div class="row">

      

     <div class="col-sm-6">

      <div class="form-group">

       <label class="control-label">Email Address <span class="required">*</span></label>

       <input class="form-control border-form-control "  name="uemail" value="{{$useremail}}" placeholder=""  type="email" required >

     </div>

   </div>



  <div class="col-sm-6">
<div class="form-group">

         <label class="control-label">Whatsapp </label>

          <input class="form-control border-form-control"  name="uphone_whatsapp" value="" placeholder=""  maxlength="10" type="text" id="uphone_whatsapp" >

        </div>

 </div>
</div>
<!--added code for ucity end -->


<div class="row">

 

 

</div>

<div class="row">

 <div class="col-sm-6" style="display:none">

  <div class="form-group">

   <label class="control-label">Zip Code <span class="required">*</span></label>

   <input class="form-control border-form-control" value="" placeholder="123456" type="number">

 </div>

</div>

<div class="col-sm-6" style="display:none;">

  <div class="form-group">

   <label class="control-label">State <span class="required">*</span></label>

   <select class="select2 form-control border-form-control">

    <option value="">Select State</option>

    <option value="AF">California</option>

    <option value="AX">Florida</option>

    <option value="AL">Georgia</option>

    <option value="DZ">Idaho</option>

  </select>

</div>

</div>

</div>



<div class="row">

  <div class="col-sm-12">

  <div class="form-group">

     <label class="control-label">City<span class="required">*</span></label>

     
     <select class="select2 form-control border-form-control" data-select2-id="16" tabindex="0" aria-hidden="false" name="ucity" required>
       <option value="" data-select2-id="18">Select City</option>
       <!-- Dynamic city add  -->

       <!-- <option value="Noida" data-select2-id="Noida" <?php if(isset($getaddress_detail->city) && $getaddress_detail->city == 'Noida'){
         //echo 'selected="selected"';
       } ?>>Noida</option>
       <option value="Greater Noida" data-select2-id="Greater Noida" <?php if(isset($getaddress_detail->city) && $getaddress_detail->city == 'Greater Noida'){
         echo 'selected="selected"';
       } ?>>Greater Noida</option> -->
     
       @foreach($get_city as $getCity)
                  @if($getCity)
                   <option value="{{$getCity->city_name}}" data-select2-id="{{$getCity->city_name}}" <?php if(isset($getaddress_detail->city) && $getaddress_detail->city == $getCity->city_name){
         echo 'selected="selected"';
       } ?>>{{$getCity->city_name}}</option>

                 
                  @endif
                  @endforeach

     </select>

   </div>
 </div>

  <div class="col-sm-12">

  <div class="form-group">

   <label class="control-label">Landmark</label>

   <input class="form-control border-form-control" name="landmark" value="{{$getaddress_detail->landmark?? ''}}" placeholder="" type="text" maxlength="120">

 </div>
 </div>

 <div class="col-sm-12">

  <div class="form-group">

   <label class="control-label">Shipping Address <span class="required">*</span></label>

   <textarea  name="uaddress" class="form-control border-form-control"  required>@if(isset($getaddress_detail->society)) {{$getaddress_detail->society}} @endif</textarea>

  <!--  <small class="text-danger">Please provide the number and street.</small> -->

 </div>
 <!--added html for delivery slot-->
</div>
<div class="col-sm-12">
    <div class="form-group">
      <label class="control-label">Delivery Type <span class="required">*</span></label>
     <select class="select2 form-control border-form-control" data-select2-id="16" tabindex="0" aria-hidden="false" name="deliveryType" id="deliveryTypeSelect" onchange="deliveryTypeChange()" required>
       <option value="" data-select2-id="18">Select Type</option>

       @foreach($delivery_type as $deliveryType)
       @if($deliveryType)
       <option value="{{$deliveryType->delivery_type}}" data-select2-id="{{$deliveryType->delivery_type}}" <?php if(isset($deliveryType->delivery_type) && $deliveryType->delivery_type == ''){
         echo 'selected="selected"';
       } ?>  data-id="{{$deliveryType->id}}">{{$deliveryType->delivery_type}}</option>
       @endif
       @endforeach

     </select>
     <input type="hidden" name="delivery_type_id"  value="0" class="form-control" id="delivery_type_id"/>

   </div>
 </div>
 <div class="col-sm-12">
 <div class="form-group">

   <label class="control-label">Choose a Delivery Slot <span class="required">*</span></label>

   
   <input type="date" name="deliveydate"  class="form-control" id="datepicker" onchange="getDateChange(this);" required />

 </div>
 
 <!--added html for delivery slot-->
 <!--added code to show time slot beg-->
 <div id="timeslotSection" class="form-group">
 <?php
    // dd(count($ReturnArray));
 $count=count($ReturnArray) -2;
 for($i=0;$i <= $count;$i++){
   ?>
   <div class="form-check">
     <input class="form-check-input timeslot" id="timeslot{{$i}}" type="radio" name="timeslot"  value="<?php echo $ReturnArray[$i].'-'.$ReturnArray[$i+1]?>" required>
     <label class="form-check-label" for="timeslot{{$i}}">
      <?php   echo $ReturnArray[$i].'-'.$ReturnArray[$i+1] ?>
    </label>
  </div>
<?php } ?>
<input class="form-check-input timeslot" type="hidden" name="timeslot"  value="">
</div>
<!--added code to show time slot end-->


</div>

</div>

<div class="heading-part" style="display:none;">

 <h3 class="sub-heading">Billing Address</h3>

</div>



<div class="row" style="display:none;">

 <div class="col-sm-6">

  <div class="form-group">

   <label class="control-label">First Name <span class="required">*</span></label>

   <input class="form-control border-form-control" value="" placeholder="Gurdeep" type="text">

 </div>

</div>

<div class="col-sm-6">

  <div class="form-group">

   <label class="control-label">Last Name <span class="required">*</span></label>

   <input class="form-control border-form-control" value="" placeholder="Osahan" type="text">

 </div>

</div>

</div>

<div class="row" style="display:none;">

 <div class="col-sm-6">

  <div class="form-group">

   <label class="control-label">Phone <span class="required">*</span></label>

   <input class="form-control border-form-control" value="" placeholder="123 456 7890" type="number">

 </div>

</div>

<div class="col-sm-6" >

  <div class="form-group">

   <label class="control-label">Email Address <span class="required">*</span></label>

   <input class="form-control border-form-control " value="" placeholder="iamosahan@gmail.com" disabled="" type="email">

 </div>

</div>

</div>

<div class="row" style="display:none;">

 <div class="col-sm-6">

  <div class="form-group">

    

  </div>

</div>

<div class="col-sm-6">

  <div class="form-group">

   <label class="control-label">City <span class="required">*</span></label>

   

 </div>

</div>

</div>

<div class="row" style="display:none;">

 <div class="col-sm-6">

  <div class="form-group">

   <label class="control-label">Zip Code <span class="required">*</span></label>

   <input class="form-control border-form-control" value="" placeholder="123456" type="number">

 </div>

</div>

<div class="col-sm-6">

  <div class="form-group">

   <label class="control-label">State <span class="required">*</span></label>

   <select class="select2 form-control border-form-control">

    <option value="">Select State</option>

    <option value="AF">California</option>

    <option value="AX">Florida</option>

    <option value="AL">Georgia</option>

    <option value="DZ">Idaho</option>

  </select>

</div>

</div>

</div>

<div class="row" style="display:none;">

 <div class="col-sm-12">

  <div class="form-group">

   <label class="control-label">Billing Landmark <span class="required">*</span></label>

   

   <small class="text-danger">

     Please include landmark (e.g : Opposite Bank) as the carrier service may find it easier to locate your address.

   </small>

 </div>

</div>

</div>
<!--added dropdown-->
<div class="row">
  

 <div class="col-sm-12">
   <div class="form-group">
    <label class="control-label">Payment Type <span class="required">*</span></label>
    <select class="select2 form-control border-form-control" data-select2-id="16" tabindex="0" aria-hidden="false" name="paymenttype" id="paymenttype" onchange="payoption()" required>
     <option value="" data-select2-id="18">Select payment</option>
     <option value="0" data-select2-id="">Cash on Delivery</option>
     <option value="2" data-select2-id="">Wallet</option>
     <option value="1" data-select2-id="">Online Payment</option>

     <!-- comment on 14-06-21 by Anish -->
     <!-- <option value="1" data-select2-id="">Google Pay</option>
     <option value="1" data-select2-id="">UPID</option>
     <option value="1" data-select2-id="">PayTm</option>
     <option value="1" data-select2-id="">Online Payment</option> -->
     <!-- end comment on 14-06-21 by Anish -->
   </select>
 </div>
</div>
<div class="col-sm-12" id="showOnlineAmountBlock" style="display:none;">
   <div style="">*<span class="noteText">Note</span> <span>:-  If you pay the amount online then you get </span><span id="OnlineDisView" style="color:#3c822b"></span> online discount. So you need to pay only <span id="OnlineTotalAmountView" style="color:#3c822b"></span>.</div> 
 </div>

<div class="col-sm-12">
 <div class="form-group">

  <label for="specialInstruction">Special Instructions if any [Optional]</label>
  <textarea class="form-control" id="specialInstruction" rows="3" name="special_instruction"></textarea>
</div>
</div>                                

</div>
<!--added dropdown-->
<!--added code for razorpay beg-->
<!--added code for razorpay end-->

<div class="custom-control custom-checkbox mb-3">

 <input type="checkbox" class="custom-control-input" id="customCheckbill" required="">

 <!--  <label class="custom-control-label" for="customCheckbill">Use my delivery address as my billing address</label> -->
 <label class="custom-control-label" for="customCheckbill">I accept the  <a href="{{url('/term-and-condition')}}" style="color: #e96125;cursor: pointer;">terms and conditions.</a></label>

</div>

<!--  <button type="button" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg">PAY</button> -->
<?php $ordertotalsrazor=$ordertotals * 100; ?>

 @if(session()->has('userData'))

<input type="submit" name="submit" data-toggle="collapseaz" data-target="#collapseThreeaz" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg" value="BUY NOW"  id="cod"/>
 @else
 <input data-toggle="collapseaz" data-container="login_register" data-href="{{ route('login-register') }}" data-target="#collapseThreeaz" aria-expanded="false" aria-controls="collapseThree" class="btn btn-secondary mb-2 btn-lg" value="BUY NOW"  id="clickTocheckRegistored"/>
             
@endif
<!--added coe for razorpay--->
                                   <!-- <div class="card-body razorbtn" style="padding-left: 0px !important;">
                                   <script src="https://checkout.razorpay.com/v1/checkout.js"
                                            data-key="{{ 'rzp_test_s27d3jedlIC8aI' }}"
                                            data-amount="{{$ordertotalsrazor}}"
                                            data-buttontext="Pay"
                                            data-name="NiceSnippets"
                                            data-description="Rozerpay"
                                            data-image="{{ asset('/image/nice.png') }}"
                                            data-prefill.name="name"
                                            data-prefill.email="email"
                                            data-theme.color="#ff7529">
                                    </script>
                                  </div> -->
                                  <!--added coe for razorpay--->


                                </form>
                                
                                <!--added code for razorpay beg--->
                                
                                
                                
                                <!--added code for razorpay end--->

                              </div>

                            </div>

                          </div>

                          <div class="card">

                           <!-- <div class="card-header" id="headingThree">

                              <h5 class="mb-0">

                                 <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">

                                 <span class="number">3</span> Payment

                                 </button>

                              </h5>

                           </div>
                         -->
                         <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">

                          <div class="card-body">

                           <form class="col-lg-8 col-md-8 mx-auto">

                            <div class="form-group">

                             <label class="control-label">Card Number</label>

                             <input class="form-control border-form-control" value="" placeholder="0000 0000 0000 0000" type="text">

                           </div>

                           <div class="row">

                             <div class="col-sm-3">

                              <div class="form-group">

                               <label class="control-label">Month</label>

                               <input class="form-control border-form-control" value="" placeholder="01" type="text">

                             </div>

                           </div>

                           <div class="col-sm-3">

                            <div class="form-group">

                             <label class="control-label">Year</label>

                             <input class="form-control border-form-control" value="" placeholder="15" type="text">

                           </div>

                         </div>

                         <div class="col-sm-3">

                         </div>

                         <div class="col-sm-3">

                          <div class="form-group">

                           <label class="control-label">CVV</label>

                           <input class="form-control border-form-control" value="" placeholder="135" type="text">

                         </div>

                       </div>

                     </div>

                     <hr>

                     <div class="custom-control custom-radio">

                       <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">

                       <label class="custom-control-label" for="customRadio1">Would you like to pay by Cash on Delivery?</label>

                     </div>

                     <p>Vestibulum semper accumsan nisi, at blandit tortor maxi'mus in phasellus malesuada sodales odio, at dapibus libero malesuada quis.</p>

                     <button type="button" type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour" class="btn btn-secondary mb-2 btn-lg">NEXT</button>

                   </form>

                 </div>

               </div>

             </div>

             <div class="card" style="display:none;">

               <div class="card-header" id="headingThree">

                <h5 class="mb-0">

                 <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">

                   <span class="number">2</span> Order Complete

                 </button>

               </h5>

             </div>

             <div id="collapsefour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">

              <div class="card-body">

               <div class="text-center">

                <div class="col-lg-10 col-md-10 mx-auto order-done">

                 <i class="mdi mdi-check-circle-outline text-secondary"></i>

                 <h4 class="text-success">Congrats! Your Order has been Accepted..</h4>

                 <p>

                  <!-- Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque lobortis tincidunt est, et euismod purus suscipit quis. Etiam euismod ornare elementum. Sed ex est, Sed ex est, consectetur eget consectetur, Lorem ipsum dolor sit amet... -->

                </p>

              </div>

              <div class="text-center">

               <a href="{{url('/')}}"><button type="submit" class="btn btn-secondary mb-2 btn-lg">Return to store</button></a>

             </div>

           </div>

         </div>

       </div>

     </div>

   </div>

 </div>

</div>

<div class="col-md-4">

  <div class="card">
    <?php 
    $totalitem=0;
    $totalcartqty=0;
                                        //$cartarray=array();
    
    ?>
    @if(session()->has('totalcartqty'))
    <?php $totalitem = session()->get('totalcartqty'); ?>
    @endif

    <h5 class="card-header">My Cart <span class="text-secondary float-right"><span>(</span><span id="totalquant"> @if(session()->has('totalcartqty')) {{$totalitem}} @endif</span><span> item)</span></h5>

    <div class="card-body pt-0 pr-0 pl-0 pb-0">
      <?php 
                            //dd($products);
      $ordertotals=0;

      $totalqty=0;
      
      if(!empty($products)) {

        foreach($products as $product){

         echo "Store id :". $storeId = $product->product_store_id."<br>";
         echo "Product id :". $productId = $product->product_id;
         $discountList  = DB::table('discounts')->select('*')->where('store_id', $storeId)->where('product_id', $productId)->get();


         

                                //dd($product);
                             /* $price_after_discount=$product->mrp*((100 -$product->discount_percentage)/100);
                              $discountprice=$product->mrp*$product->discount_percentage/100;
                              $ordertotals+=$price_after_discount * $qty[$product->varient_id];*/
                               $price_after_discount=$product->price;
                              //$discountprice=$product->mrp*$product->discount_percentage/100;
                              $discountdata=(($product->mrp - $product->price)/$product->mrp)* 100;
                              $ordertotals+=$price_after_discount * $qty[$product->varient_id];                          

                              ?>

                              <div class="cart-list-product" id="singleitem{{$product->varient_id}}">
                               <input type="hidden" name="stockvalue" value="{{$product->stock ?? ''}}" id="stockvalue{{$product->varient_id}}">
                               <input type="hidden" value="{{$qty[$product->varient_id]}}" id="popup_cart_qty{{$product->varient_id}}">

                               <a class="float-right remove-cart" href="#"><i class="mdi mdi-close" onclick="removeproduct('{{$product->varient_id}}')"></i></a>

                               <img class="img-fluid" src="@if(!empty($product->varient_image)){{url('admin-panel/'.$product->varient_image)}} @else {{url('admin-panel/'.$product->product_image)}} @endif" alt="">

                               <span class="badge badge-success">@if($discountdata > 0) {{round($discountdata,2)}}% OFF @endif</span>

                               <h5><a href="#">{{$product->ctitle}}</a></h5>

                               <h6><strong><span class="mdi mdi-approval"></span> Available in</strong> - {{$product->quantity}} {{$product->unit}}</h6>
                               <!-- <h6><strong><span class="mdi mdi-approval"></span> Available Stocks</strong> - {{$product->quantity}}</h6> -->

                               <p class="offer-price mb-0">???<span id="priceafterdis{{$product->pid}}">{{round($price_after_discount,2)}}</span> <i class="mdi mdi-tag-outline"></i> <span class="regular-price"> @if($product->discount_percentage > 0) ???{{round($product->mrp,2)}} @endif</span></p>

                               <!--added code for single product total price beg-->
                               <p calss="price_qty_tab"><span class="price_dis">???{{$price_after_discount}}X</span><span class="quant_single{{$product->varient_id}}">{{$qty[$product->varient_id]}}</span><span>=???</span><span class="single_product_total{{$product->varient_id}}">{{$price_after_discount * $qty[$product->varient_id] }}</span></p>
                               <!--added code for single product total price end-->
                               <!--added code for inc dec qty beg 10 nov 2020-->
                               <p class="offer-price mb-0 quantity" style="">
                                 <span class="input-group-btn"><button disabled="disabled" class="btn btn-theme-round btn-number" type="button"><span onclick="cartupdate('{{$product->varient_id}}','dec')" class="dec{{$product->varient_id}}" style="font-size:28px">@if($qty[$product->varient_id]==1) x @else - @endif </span></button></span>
                                 <span><input type="text" max="10" min="1" value="{{$qty[$product->varient_id]}}" name="quant[1]" class="quant{{$product->varient_id}}" disabled></span>
                                 <span class="input-group-btn"><button class="btn btn-theme-round btn-number" type="button">

                                    <span onclick="cartupdate('{{$product->varient_id}}','inc')" class="inc{{$product->varient_id}}">+</span></button>

                               </span>
                             </p>
                             <!--added code for inc dec qty end 10 nov 2020-->

                           </div>

                         <?php } 
                       }

                       ?>



                       {{-- added code for bulk order discount amount  beg--}}

                       <div class="bulk_details" style=" display:{{$viewBulk??'none'}}">
                        <div style="width:70%;float:left;"><h5>Bulk Order Discount</h5></div>
                        <div class="otsidebar" style="width:30%;float:left;font-weight: bold;">???<span class="bulk_amt">{{$bulkOrderDiscount}}</span></div>
                      </div>
                      {{-- added code for bulk order discount amount end --}}

                       {{-- added code for coupon amount  beg--}}
                       <div class="coupon_details" style="display:none;">
                        <div style="width:70%;float:left;"><h5>Coupon Amount</h5></div>
                        <div class="otsidebar" style="width:30%;float:left;font-weight: bold;">???<span class="coup_amt">0</span></div>
                      </div>
                      {{-- added code for amount end --}}
                      {{-- delivery charge beg --}}
                      @php     $charge=0;  @endphp
                      @if(session()->has('delivercharge'))
                      
                      @php     $charge=session()->get('delivercharge');  @endphp
                      
                      @endif
                      @if(session()->has('bulkOrderDiscount'))
                      
                      @php     $bulkOrderDiscount=session()->get('bulkOrderDiscount');  @endphp
                      
                      @endif
                      @if($ordertotals == 0)
                      @php $charge=0;$bulkOrderDiscount=0; @endphp
                      @endif
                      <div class="deliverychargeContainer" style="">
                        <div style="width:70%;float:left;"><h5>Delivery Charge</h5></div>
                        <div class="deliveryamount" style="width:30%;float:left;font-weight: bold;">???<span class="delivery_amt">{{$charge}}</span></div>
                      </div>
                      {{-- delivery charge end --}}
                      @if($ordertotals == 0)
                      @php $charge=0; @endphp
                      @endif
                      {{-- added code for order total  beg--}}
                      <div class="cartdiv">
                        <div style="width:50%;float:left;"><h4>Total</h4></div>
                        <div class="otsidebar" style="width:50%;float:left;font-weight: bold;">???<span class="otsidebarTotal">{{$ordertotals}}</span></div>
                      </div>
                      {{-- added code for order total end --}}
                      {{-- added code to show grandtotal beg --}}
                      <div class="cartdivgrand">
                        <div style="width: 62%;float: left;"><h4>Grand Total</h4></div>
    
                        <div class="otsidebar" style="width: 38%; font-weight: bold;float: right;">
						 ???<span class="otsidebarTotalgrand">{{$ordertotals + $charge-$bulkOrderDiscount }}</span></div>
                      </div>
                      {{-- added code to show grandtotal end --}}

                      

                    </div>

                  </div>
                  {{-- add code to apply coupon beg--}}
                  @if(session()->has('userData')) 
                  <div class="coupon_container">
                    <div class="row">
                    <div class="coupon_left" style="width:70%;float:left;">
                     <input type="text" name="coupon_code" id="coupon_code" placeholder="Enter your coupon code" class="form-control border-form-control">
                   </div>
                   <div class="coupon_right" style="width:30%;float:right;text-align: right;">
                     <input type="button" name="apply_coupon" value="Apply" class="btn btn-secondary mb-2 btn-lg" onclick="set_coupon()" />
                     <input type="hidden" name="coupon_apply_limit" id="coupon_apply_limit" value="0"/>
                     
                   </div>
                   <div class="coupon_msg" style="color:red;font-size: 15px;font-weight: bold;"></div>
                   </div>

                   <div class="row couponViewbtn">
                      <button  data-toggle="modal" data-target="#couponModal"  class="btn btn-success">
                        <i class="mdi mdi-ticket"></i> View Coupons
                      </button>
                        
                    </div>
                 </div>
                 @endif
                 {{-- add code to apply coupon end--}}
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
 <div class="modal fade couponModal" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="couponModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title heading-design-h5" id="couponModalLabel">Coupon List</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body bodyCss">
                        <div class="row bodyHeader">
                        <div class="col col-3"><p>Coupon Name (code)</p></div>
                        <div class="col col-4"><p>Description</p></div>
                        <div class="col col-3"><p>Amount (Type)</p></div>
                        <div class="col col-2"><p>Select</p></div>
                        </div>
                      @if(count($coupons)>0)
                                      @php $i=1; @endphp
                                      
             
                        @foreach($coupons as $coupon)
                           <div class="row">
                            <div class="col col-3">
                              <p>{{$coupon->coupon_name}}<br>({{$coupon->coupon_code}})</p>
                            </div>
                            <div class="col col-4"><p>{{$coupon->coupon_description}}</p></div>
                            <div class="col col-3"> 
                            <p>
                              <span style="">{{$coupon->amount}}</span>
                              {<span style="text-transform: capitalize;">{{$coupon->type}}</span>)
                            </p>
                           </div>
                           <div class="col col-2"> 
                            <button type="button" class="btn btn-secondary couponSelectBtn" onclick="select_coupon('{{$coupon->coupon_code}}')" data-coupon-id="{{$coupon->coupon_id}}" data-coupon-code="{{$coupon->coupon_code}}" data-dismiss="modal" aria-label="Close"><span style="color:#fff"><i class="mdi mdi-check"></i></span></button>
                              
                           </div>
                         </div>
                            @php $i++; @endphp
                            @endforeach
                          @else
                            <div class="row">
                            <div class="col-sm-12">
                              <p class="noDataMsg">No Coupons Found</p>
                            </div>
                           </div> 
                                  @endif
             </div> 
             <div class="modal-footer">
               
             </div>                    

            </div>
          </div>
        </div>


 <?php //include( 'footer.php') ?>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    {{--  <script type="text/javascript">
         $(document).ready(function(){
           
            var result = <?=$discountList; ?>;
                  console.log(result);

            $.each(result, function(key, value){
                  var min = value.min;
                  var max = value.max;
                  var discountVal = value.discount;
            });
           
         });
      </script> --}}
 

 <script>

   $(document).ready(function(){
    //added code for validation of name and last name beg
    
    $( "#ufname" ).keypress(function(e) {
      var key = e.keyCode;
      if (key >= 48 && key <= 57) {
        e.preventDefault();
      }
    });
    $( "#ulname" ).keypress(function(e) {
      var key = e.keyCode;
      if (key >= 48 && key <= 57) {
        e.preventDefault();
      }
    });

    
    //added code for validation of name and last name end
    //validation for uphone beg
    $('#uphone').keypress(function (e){

      var mobile=$('#uphone').val();
      var key = e.keyCode;
      if (key < 48 || key > 57) {
        e.preventDefault();
      }
    //alert(mobile);
    if(isNaN(mobile)){
      if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
      $('#phone_error').text('Only Numbers are allowed');
     // $('#login_number_error').text('Only Numbers are allowed');
     // $('#login-submit').prop('disabled', true);
       //if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
       //added
       
       //added
       
     }else{
      $('#phone_error').text('');
      //$('#login-submit').prop('disabled', false);
    }

    if(mobile.length > 10){
       $('#phone_error').text('Mobile no. must not exceed 10 digit');
    }else if(mobile.length < 10 ){
         $('#phone_error').text('Mobile no. must be of 10 digit');
       }else{
        $('#phone_error').text('');
      }
      

    });
    //validation for uphone end

    //validation for whatsapp number beg
    $('#uphone_whatsapp').keypress(function (e){

      var whats_mobile=$('#uphone_whatsapp').val();
      var key = e.keyCode;
      if (key < 48 || key > 57) {
        e.preventDefault();
      }
    //alert(mobile);
    if(isNaN(whats_mobile)){
      if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
      //$('#phone_error').text('Only Numbers are allowed');
     // $('#login_number_error').text('Only Numbers are allowed');
     // $('#login-submit').prop('disabled', true);
       //if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'');
       //added
       
       //added
       
     }else{
      //$('#phone_error').text('');
      //$('#login-submit').prop('disabled', false);
    }

    if(whats_mobile.length > 10){
       //$('#phone_error').text('Mobile no. must not exceed 10 digit');
    }else if(whats_mobile.length < 10 ){
         //$('#phone_error').text('Mobile no. must be of 10 digit');
       }else{
        //$('#phone_error').text('');
      }
      

    });
    //validation for whatsapp end



    

  });
/*$(document).on('click', '#clickTocheckRegistored', function(e) {
      e.preventDefault();
      var container = $(this).data("container");

      $.ajax({
        url      : $(this).data("href"),
        dataType : "html",
        success  : function(result) {
          
          $('div.'+container).html(result).modal('show');
        }
      });
  });*/

    

   


//////////////////////


////////////////////////

//Delivery type change event
 function deliveryTypeChange(){
   var delivery_type = $( "#deliveryTypeSelect option:selected" ).val();
   var deliveryTypeId = $( "#deliveryTypeSelect option:selected" ).data('id');
   if(deliveryTypeId){
      console.log(deliveryTypeId);
      $( "#delivery_type_id" ).val(deliveryTypeId); 
   }else{
      $( "#delivery_type_id" ).val(0);
   }
   
   console.log(deliveryTypeId+' :: '+delivery_type);
   if(deliveryTypeId=='2'){
      $(".timeslot").attr("required", false);
      $(".timeslot").prop('checked', false);
      $(".timeslot").prop('disabled', true);
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth()+1; //As January is 0.
      var yyyy = today.getFullYear();

      if(dd<10) dd='0'+dd;
      if(mm<10) mm='0'+mm;

      var date = yyyy+'-'+mm+'-'+dd;

      $("#datepicker").val(date);
      getDateChange(date);
      $("#datepicker").attr('readOnly',true);
      sweetAlert('Message', "You can not cancel the 'Express Delivery' Order after successfully submit the order", 'info');
   }else{
      $("#datepicker").attr('readOnly',false);
      $("#datepicker").val('');
      $(".timeslot").attr("required", true);
      $(".timeslot").prop('disabled', false);
   }

 }
 //function to change timeslot based on date
 function getDateChange(ev){
   console.log(ev);
   var senData='';
   if(ev.value && ev.value != ''){
     senData=ev.value;
   
   }else{
     senData=ev;
   } 
      $.ajax({
       url:"{{url('/get_timeslot')}}",
       type:"POST",
       data:{
        "_token": "{{ csrf_token() }}",
        cdate:senData,
        
      },
      success:function(response){
        console.log(response);
        var deliveryTypeId = $( "#deliveryTypeSelect option:selected" ).data('id');
        $('#timeslotSection').html(response.newHtml);
        if(deliveryTypeId=='2'){
          $(".timeslot").attr("required", false);
          $(".timeslot").prop('checked', false);
          $(".timeslot").prop('disabled', true);

        }else{
          $(".timeslot").attr("required", true);
          $(".timeslot").prop('disabled', false);
        }

      },
    });
 }

 //added code to manage inc/dec qty end
 //aadded code for onchnag payment option beg
 function payoption(){
   //alert('gh');
   $("#showOnlineAmountBlock").css('display','none');
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

   $('#checkout_form').attr('action', "payment-inititate-request");

    var ordertotal= $('.otsidebarTotal').text();
    var storebasedData= $('#storeWiseDetail').val();
    var responseData='';
      $.ajax({
       url:"{{url('/check_online_discount')}}",
       type:"POST",
       data:{
        "_token": "{{ csrf_token() }}",
        ordertotal:ordertotal,
        storebasedData:storebasedData, 
      },
      success:function(response){
        console.log(response);
        if(response){
          console.log(response.online_payment_discount);
          if(response.online_payment_discount>0){
            $("#showOnlineAmountBlock").css('display','block');
            $("#OnlineDisView").html('???'+response.online_payment_discount);
            $("#OnlineTotalAmountView").html('???'+response.onlinetotal);
          }else{
            $("#showOnlineAmountBlock").css('display','none');
          }
          
        }
      },
      error:function(err){
        responseData = '';
        $("#showOnlineAmountBlock").css('display','none');
      },
    });

 }
   //added code to pay by wallet beg
   if(payment_type == 2){

      // alert(payment_type);
  // $('#cod').hide();
   //$('input.razorpay-payment-button').show();
   $('#checkout_form').attr('action', "payment-by-wallet");


 }
   //added code to pay by wallet end
 }
 //aadded code for onchnag payment option end

 //

 

</script>
<script>
  var today = new Date();
  var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
// var lastday = today.getDate()+30;
if(dd<10){
  dd='0'+dd
} 
if(mm<10){
  mm='0'+mm
} 


today = yyyy+'-'+mm+'-'+dd;
// lastday = yyyy+'-'+mm+'-'+dd;
document.getElementById("datepicker").setAttribute("min", today);
// document.getElementById("datepicker").setAttribute("max", lastday);
//added code to set coupon beg

function select_coupon(code){
  console.log(code);
  $("#coupon_code").val(code);

}
function set_coupon(){
  console.log('coupon');
  //$('.cartdivgrand').hide();
  
  var coupon_apply_limit= $('#coupon_apply_limit').val();

  var coupon_code= $('#coupon_code').val();
  var ordertotal= $('.otsidebarTotal').text();
  var storebasedData= $('#storeWiseDetail').val();
  console.log(storebasedData);

  //dd(ordertotal);
   //alert(coupon_code);
   if(coupon_code != ''){
      //ajax claa beg
      $.ajax({
       url:"{{url('/set_coupon')}}",
       type:"POST",
       data:{
        "_token": "{{ csrf_token() }}",
        coupon_code:coupon_code,
        ordertotal:ordertotal,
        storebasedData:storebasedData,
        coupon_apply_limit:coupon_apply_limit,
        
      },
      success:function(response){
        console.log(response);
        $('.coupon_msg').html(response.message);
        
        $('#coupon_apply_limit').val(response.coupon_apply_limit );
        var coupon_apply_limit= $('#coupon_apply_limit').val();
        //alert(coupon_apply_limit+' @ '+(coupon_apply_limit < 2));

        if(coupon_apply_limit < 2){
          $('#ot').val(response.final_price);

          
          if(response.coupon_amount > 0) {
            $('.coupon_details').show();
            $('.coup_amt').html(response.coupon_amount);
            $('.otsidebarTotal').html(response.final_price);
            var deliveycharge=$('.delivery_amt').text();
            $('.otsidebarTotalgrand').html(response.final_price + parseInt(deliveycharge));
          }
        }

      },
    });
      //ajax claa end
    }
  }

  //added code to set coupon end  



</script>

@endsection