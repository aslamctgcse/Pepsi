
@extends('website.layouts.app')
@section('content')
 @if(session()->has('userData'))
                           
<?php
    $username='';
    $usermobile='';
 $ct= session()->get('userData'); //->user_name;
// dd($ct);
    $username=ucfirst($ct->user_name);
    $usermobile=ucfirst($ct->user_phone);
                             ?>
 @endif
 <style>
   .modal-backdrop.show:nth-of-type(even) {
    z-index: 999 !important;
}
/*css for order track beg*/
.card_extra{
   background-color: #ECEFF1;
    border-radius: 10px;
     z-index: 0;
   
    padding-bottom: 20px;
}
.card {
    /*z-index: 0;
   
    padding-bottom: 20px;*/
   /* margin-top: 90px;
    margin-bottom: 90px;*/
   
}

.top {
    padding-top: 20px;
    padding-left: 13% !important;
    padding-right: 13% !important
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: #455A64;
    padding-left: 0px;
    margin-top: 30px
}

#progressbar li {
    list-style-type: none;
    font-size: 13px;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400
}

#progressbar .step0:before {
    font-family: FontAwesome;
    content: "\f10c";
    color: #fff
}

#progressbar li:before {
    width: 40px;
    height: 40px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    background: #C5CAE9;
    border-radius: 50%;
    margin: auto;
    padding: 0px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 12px;
    background: #C5CAE9;
    position: absolute;
    left: 0;
    top: 16px;
    z-index: -1
}

#progressbar li:last-child:after {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    position: absolute;
    left: -50%
}

#progressbar li:nth-child(2):after,
#progressbar li:nth-child(3):after {
    left: -50%
}

#progressbar li:first-child:after {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    position: absolute;
    left: 50%
}

#progressbar li:last-child:after {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px
}

#progressbar li:first-child:after {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: #651FFF
}

#progressbar li.active:before {
    font-family: FontAwesome;
    content: "\f00c"
}

.icon {
    width: 60px;
    height: 60px;
    margin-right: 15px
}

.icon-content {
    padding-bottom: 20px
}
.newModalCss{
  margin: auto;
}
.newModalCss1{
  padding: 10px 40px;
}
.newModalP{
  margin: 17px 1px; 
}
.modal-title{
    width: 100%;
    text-align: center;
    padding-left: 40px;
}

.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    max-width: 160px;
}

.rating>input {
    display: none
}

.rating>label {
    position: relative;
    width: 33px;
    font-size: 34px;
    color: #FFD600;
    cursor: pointer
}

.rating>label::before {
    content: "\2605";
    position: absolute;
    opacity: 0
}

.rating>label:hover:before,
.rating>label:hover~label:before {
    opacity: 1 !important
}

.rating>input:checked~label:before {
    opacity: 1
}

.rating:hover>input:checked~label:before {
    opacity: 0.4
}


.rating1 {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    max-width: 138px;
}

.rating1>input {
    display: none
}

.rating1>label {
    position: relative;
    width: 28px;
    font-size: 30px;
    color: #FFD600;
    cursor: pointer
}

.rating1>label::before {
    content: "\2605";
    position: absolute;
    opacity: 0
}

.rating1>label:hover:before,
.rating1>label:hover~label:before {
    /*opacity: 1 !important*/
}

.rating1>input:checked~label:before {
    opacity: 1
}

.rating1:hover>input:checked~label:before {
    /*opacity: 0.4*/
}
.ratingHeader{
      border-bottom: 1px solid grey;
    padding-bottom: 5px;
    margin-bottom: 20px;
}
.ratingTitle{

}
.ratingTime{
  margin-bottom: 0px;
    padding: 10px;
}
.modal-content{
  border-radius: 6px;

}
.confirmbtnCss input{
  border-radius: 4px!important;
  min-width: 80px;
}
/*css for order track end*/

 </style>
 <link href="https://irfandurmus.com/assets/star-rating/rating.css" rel="stylesheet" type="text/css">


	<section class="account-page section-padding">
    @if(session()->has('message'))
    <div class="alert alert-success" style="text-align:center;">
      {{ session()->get('message') }}
    </div>
    @endif

         <div class="container">

            <div class="row">

               <div class="col-lg-9 mx-auto">

                  <div class="row no-gutters">  

                     <div class="col-md-4">

                        <div class="card account-left">

                           <div class="user-profile-header">

                              <img alt="logo" src="{{ url('assets/website/img/dealwy-logo.png') }}">

                              <h5 class="mb-1 text-secondary"><strong>Hi </strong>{{$username}}</h5>

                              <p> +91 {{$usermobile}}</p>


                           </div>

                           <div class="list-group">

                              <a href="{{url('/my-profile')}}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-account-outline"></i>  My Profile</a>

                              <a href="{{url('/my-profile')}}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address</a>

                              <!-- //comment till pending work -->
                              <!-- <a href="{{url('/wallet')}}" class="list-group-item list-group-item-action"><i class="fa fa-gift"></i>  Wallet </a> 
                              <a href="{{url('/rewards')}}" class="list-group-item list-group-item-action"><i class="fa fa-gift"></i>  Rewards </a>  -->

                              <!-- <a href="wishlist.php" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-heart-outline"></i>  Wish List </a> -->

                              <a href="{{url('/orders')}}" class="list-group-item list-group-item-action active"><i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i>  Order List</a> 

                              <a href="{{ route('user-logout') }}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-lock"></i>  Logout</a> 

                           </div>

                        </div>

                     </div>

                     <div class="col-md-8">

                        <div class="card card-body account-right">

                           <div class="widget">

                              <div class="section-header">

                                 <h5 class="heading-design-h5">
                                    <?php
                                        // $year= substr(date("Y"),-2);
                                        // $month=date('m');
                                        // $date=date('d');
                                        // $orderno=$year.$month.$date.'-'.$orderid->order_id;

                                     ?>

                                   {{'Sub Order ID : '.$orderid->sub_order_cart_id }}{{' ( Payment mode: '.strtoupper($orderid->payment_method ?? '').')'}} 

                                 </h5>
                                 <!-- <p style="color: #000;font-weight:bold;">Delivery Charge: ₹ {{$orderid->delivery_charge}}</p> -->

                              </div>

                              <div class="order-list-tabel-main table-responsive">

                                 <table class="datatabel table table-striped table-bordered order-list-tabel" width="100%" cellspacing="0">

                                    <thead>

                                       <tr>

                                          <th>S.No #</th>

                                          <th>Items</th>
                                          <th>Image</th>

                                          <th>Rate</th>

                                          <th>Quantity</th>

                                          <th>Subtotal</th>
                                        @if(isset($orderid->order_status) && $orderid->order_status == 'Completed' || $orderid->order_status == 'completed')
                                        <th>Delivered Item</th>
                                        @else  
                                          <th>Cancel Item</th>
                                        @endif  

                                       </tr>

                                    </thead>

                                    <tbody>
                                         <?php 
                                        $i=1;
                                        $ordertotal=0;

                                     ?>
                                     @foreach($orderproducts as $orderproduct)


                                       <tr>

                                          <td>#{{$i}}</td>

                                          <td>
                                            {{$orderproduct->product_name}}
                                            

                                          </td>
                                          <td>
                                            
                                            <img src=" @if(isset($orderproduct->varient_image)) {{url('/admin-panel/'.$orderproduct->varient_image)}} @endif" />
                                          </td>
                                        

                                          <td>₹{{$orderproduct->actual_price}}</td>

                                          <td>{{$orderproduct->qty}}</td>

                                          <td>₹{{$orderproduct->price}}
                                          
                                          </td>
                              @if(isset($orderid->order_status) && $orderid->order_status == 'Completed' || $orderid->order_status == 'completed')
                                <td><i class="fa fa-check" style="cursor:pointer;color:green;"></i></td>
                              @else
                                          
                              <td>
                                <?php if($orderid->delivery_type_id=='2'){ ?>
                                  NA

                                <?php }else{ ?>
                                  <i class="fa fa-times" aria-hidden="true" data-toggle="modal" data-target="#cancelItemModal{{$orderproduct->varient_id}}" style="cursor:pointer;"></i>
                                <?php } ?>  
                                
                                


                                 {{-- add modal form for delete item beg --}}
            <div class="modal fade ordmodalCss" id="cancelItemModal{{$orderproduct->varient_id}}" tabindex="-1" role="dialog" aria-labelledby="cancelmodalItemLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="cancelmodalItemLabel">Cancel Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body newModalCss">
                    <div class="delete_order">
                       <form method="post" action="{{url('/cancel-item')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                            <!-- hidden field for cat and varient id-->
                            <!-- <div class="form-group">
                                <label for="exampleFormControlTextarea1">Reason for Cancellation</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="cancel_reason"></textarea>
                                
                                  <input type="hidden" name="cartid" value="{{$orderproduct->order_cart_id}}">
                                  <input type="hidden" name="varientid" value="{{$orderproduct->varient_id}}">
                                  <input type="hidden" name="userid" value="{{$orderid->user_id}}">
                                  <input type="hidden" name="removed_item_total_price" value="{{$orderproduct->price * $orderproduct->qty}}">
                           </div>
                           <div class="form-group">
                            <label for="exampleFormControlFile1">Attach image</label>
                            <input type="file" class="form-control-file" id="exampleFormControlFile1" name="cancel_image">
                          </div>
                           <input  type="submit" class="btn btn-primary mb-2" name="submit" value="Cancel Order"> -->

                           <div class="form-group">
                                <h6 class="newModalP" >Are you sure you want to cancel item?</h6>

                                  <label for="exampleFormControlTextarea1">Reason for Cancellation</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="cancel_reason"></textarea>                              
                                  <input type="hidden" name="cartid" value="{{$orderproduct->order_cart_id}}">
                                  <input type="hidden" name="subcartid" value="{{$orderproduct->sub_order_cart_id}}">
                                  <input type="hidden" name="orderItemId" value="{{$orderproduct->store_order_id}}">
                                  <input type="hidden" name="varientid" value="{{$orderproduct->varient_id}}">
                                  <input type="hidden" name="userid" value="{{$orderid->user_id}}">
                                  <input type="hidden" name="removed_item_total_price" value="{{$orderproduct->price}}">
                           </div>
                           <div class="form-group confirmbtnCss" style="text-align: center;">
                            <input  type="submit" class="btn btn-primary mb-2" name="submit" value="Yes">
                            <input  type="button" class="btn btn-primary mb-2" data-dismiss="modal" aria-label="Close" value="No">
                          </div>
                           

                        </form>
                      </div>
                  </div>
                </div>
                </div>
</div>

                                 {{-- add modal form for delete item end --}}


                              </td>
                              @endif

                                       </tr>
                                       <?php $i++; 
                                         $ordertotal+=$orderproduct->price * $orderproduct->qty;

                                       ?>
                                       @endforeach
                                        @if($orderid->delivery_charge>0)
                                       <tr>
                                        <th colspan="6">Delivery Charge</th>
                                          <th colspan="">+₹{{$orderid->delivery_charge}}</th>
                                       </tr>
                                       @endif
                                        @if($orderid->coupon_discount>0)
                                       <tr>
                                        <th colspan="6">Discount</th>
                                          <th colspan="">-₹{{$orderid->coupon_discount}}</th>
                                       </tr>
                                       @endif
                                        @if($orderid->bulk_order_based_discount>0)
                                       <tr>
                                        <th colspan="6">Bulk Order Discount</th>
                                          <th colspan="">-₹{{$orderid->bulk_order_based_discount}}</th>
                                       </tr>
                                       @endif
                                        @if($orderid->online_payment_based_discount>0)
                                       <tr>
                                        <th colspan="6">Online Payment Discount</th>
                                          <th colspan="">-₹{{$orderid->online_payment_based_discount}}</th>
                                       </tr>
                                       @endif
                                       <tr>
                                      <?php 
                                       $discount_text='';
                                      if($coupon_discount != '' && $coupon_discount >0){
                                        $discount_text= '(Discount: ₹'.$coupon_discount.')';
                                      }
                                      ?>
                                          <th colspan="6">Total{{-- $discount_text --}} </th>
                                          <!-- <th colspan="">{{-- $ordertotal --}}</th> -->
                                          <th colspan="">₹{{$order_total_price}}</th>

                                       </tr>
                                       <!-- cancel order-->
                                       <tr>
                                         @if(isset($orderid->order_status) && $orderid->order_status == 'Completed' || $orderid->order_status == 'completed')
                                         <th colspan="7" style="text-align: right;">
                                         
                                          @if($reviewDetails) 
                                          <p style="font-size:12px">*Order delivered successfully.</p> 
                                         <div class="row" style="text-align:left;">
                                          <div class="col-sm-12">
                                            <h5 class="ratingHeader">Rating & Review</h5>
                                          </div>
                                          <div class="col-sm-12 ratingTitle" >{{$reviewDetails->order_comment}}</div>
                                          <div class="col-sm-3">
                                            <div class="rating1" id="ratingIdValue"> 
                                              <input type="radio" name="rating" value="5" id="5" @if($reviewDetails->order_rating=='5') checked="true" @endif readonly><label for="5">☆</label> 
                                              <input type="radio" name="rating" value="4" id="4" @if($reviewDetails->order_rating=='4') checked="true" @endif readonly><label for="4">☆</label> 
                                              <input type="radio" name="rating" value="3" @if($reviewDetails->order_rating=='3') checked="true" @endif id="3" readonly><label for="3">☆</label> 
                                              <input type="radio" name="rating" value="2" id="2" @if($reviewDetails->order_rating=='2') checked="true" @endif readonly><label for="2">☆</label> 
                                              <input type="radio" name="rating" value="1" id="1" @if($reviewDetails->order_rating=='1') checked="true" @endif readonly><label for="1">☆</label>
                                            </div>
                                          </div>
                                          <div class="col-sm-9 ratingTime">
                                            <p>{{date('d-m-Y', strtotime($reviewDetails->created_at))}}</p>
                                          </div>
                                         </div>
                                         @else
                                         <span class="btn btn-primary"  data-toggle="modal" data-target="#reviewmodal">Rating & Review</span>
                                            <p style="font-size:12px">*Order delivered successfully.</p>

                                         @endif 


                                         </th>
                                         

                                         @else
                                          <th colspan="7" style="text-align: right;">
                                            <?php if(isset($orderid->order_status) && $orderid->order_status != 'Cancelled') { ?>
                                            <?php if($orderid->delivery_type_id=='2'){ ?>  
                                            <span class="btn btn-danger"  disabled='true' style="cursor:not-allowed;;background-color: grey">Cancel  Order</span>
                                            <p style="font-size:12px">*Express Delivery orders cannot be canceled</p>
                                            <?php }else{?>
                                              <span class="btn btn-danger"  data-toggle="modal" data-target="#cancelmodal">Cancel  Order</span>
                                            <?php }?>  

                                            <!--added code for cancellation whole order beg-->
                                          <?php }else{?>
                                            <span class="btn btn-danger">Cancelled</span>
                                          <?php  } ?>
                                          <!--added code for cancellation whole order end-->
                                           </th>
                                           @endif
                                          

                                       </tr>
                                       <!-- cancel order-->


                                      

                                    </tbody>

                                 </table>


                                 



                              </div>

                              <!-- added code to show order tracking beg-->
                                 <div class="orderTrackContainer">
                                    <div class="container px-1 px-md-4 py-5 mx-auto">
                                    <div class="card card_extra" style="margin-top:0px !important;">
                                        <div class="row d-flex justify-content-between px-3 top">
                                            <div class="d-flex">
                                                <h5>ORDER TRACK
                                                 <!--  <span class="text-primary font-weight-bold">#Y34XDHR</span> -->
                                                </h5>
                                            </div>
                                            <div class="d-flex flex-column text-sm-right">
                                                <!-- <p class="mb-0">Expected Arrival <span>01/12/19</span></p>
                                                <p>USPS <span class="font-weight-bold">234094567242423422898</span></p> -->
                                            </div>
                                        </div> <!-- Add class 'active' to progress -->
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-12">
                                              <?php 
                                              $addActiveClass_pending='';
                                                 $addActiveClass_confirmed='';
                                                 $addActiveClass_outfordilivery='';
                                                 $addActiveClass_deliverd='';
                                                //code to add active class on the basis of order status beg
                                                 #pending
                                              if(isset($orderid->order_status) && $orderid->order_status == 'Pending' || $orderid->order_status == 'pending'){
                                                 $addActiveClass_pending='active';
                                                 $addActiveClass_confirmed='';
                                                 $addActiveClass_outfordilivery='';
                                                 $addActiveClass_deliverd='';
                                              }
                                              #confirmed
                                               if(isset($orderid->order_status) && $orderid->order_status == 'Confirmed' || $orderid->order_status == 'confirmed'){
                                                 $addActiveClass_pending='active';
                                                 $addActiveClass_confirmed='active';
                                                 $addActiveClass_outfordilivery='';
                                                 $addActiveClass_deliverd='';
                                              }
                                              #out for delivery
                                              if(isset($orderid->order_status) && $orderid->order_status == 'Out_For_Delivery' ){
                                                 $addActiveClass_pending='active';
                                                 $addActiveClass_confirmed='active';
                                                 $addActiveClass_outfordilivery='active';
                                                 $addActiveClass_deliverd='';
                                              }
                                              #delivered
                                              if(isset($orderid->order_status) && $orderid->order_status == 'Completed' || $orderid->order_status == 'completed'){
                                                 $addActiveClass_pending='active';
                                                 $addActiveClass_confirmed='active';
                                                 $addActiveClass_outfordilivery='active';
                                                 $addActiveClass_deliverd='active';
                                              }
                                                //code to add active class on the basis of order status end
                                              
                                              ?>
                                                <ul id="progressbar" class="text-center">
                                                    <li class="{{ $addActiveClass_pending}} step0"></li>
                                                    <li class=" {{ $addActiveClass_confirmed}} step0"></li>
                                                    <li class=" {{ $addActiveClass_outfordilivery}} step0"></li>
                                                    <li class="{{ $addActiveClass_deliverd}} step0"></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row justify-content-between top">
                                            <div class="row d-flex icon-content"> 
                                              <!-- <img class="icon" src="https://i.imgur.com/9nnc9Et.png"> -->
                                                <div class="d-flex flex-column">
                                                    <p class="font-weight-bold">Pending</p>
                                                </div>
                                            </div>
                                            <div class="row d-flex icon-content"> 
                                             <!--  <img class="icon" src="https://i.imgur.com/u1AzR7w.png"> -->
                                                <div class="d-flex flex-column">
                                                    <p class="font-weight-bold">Confirmed</p>
                                                </div>
                                            </div>
                                            <div class="row d-flex icon-content"> 
                                             <!--  <img class="icon" src="https://i.imgur.com/TkPm63y.png"> -->
                                                <div class="d-flex flex-column">
                                                    <p class="font-weight-bold">Ready For<br>Pickup</p>
                                                </div>
                                            </div>
                                            <div class="row d-flex icon-content"> 
                                              <!-- <img class="icon" src="https://i.imgur.com/HdsziHP.png"> -->
                                                <div class="d-flex flex-column">
                                                    <p class="font-weight-bold">Delivered</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>

                                 </div>
                                 <!-- added code to show order tracking end-->

                           </div>

                        </div>

                     </div>

                  </div>

               </div>

            </div>

         </div>

      </section>


      <!-- Modal -->
<div class="modal fade ordmodalCss" id="cancelmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cancel Sub Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body newModalCss">
        <div class="delete_order">
           <form method="post" action=" @if(isset($orderproduct->order_cart_id))  {{url('/delete_sub_order/'.$orderid->cart_id.'/'.$orderid->sub_order_id)}} @endif" enctype="multipart/form-data">
            {{csrf_field()}}
                <!-- <div class="form-group">
                    <label for="exampleFormControlTextarea1">Reason for Cancellation</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="cancel_reason"></textarea>
               </div>
               <div class="form-group">
                <label for="exampleFormControlFile1">Attach image</label>
                <input type="file" class="form-control-file" id="exampleFormControlFile1" name="cancel_image">
              </div>
               <input  type="submit" class="btn btn-primary mb-2" name="submit" value="Cancel Order"> -->
               <div class="form-group">
                    <h6 class="newModalP" >Are you sure you want to cancel your order?</h6>
                    <label for="exampleFormControlTextarea1">Reason for Cancellation</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="cancel_reason"></textarea>
                    <!-- <input type="hidden" id="exampleFormControlTextarea1" name="cancel_reason" value=""> -->
               </div>
               <div class="form-group confirmbtnCss" style="text-align: center;">
                            <input  type="submit" class="btn btn-primary mb-2" name="submit" value="Yes">
                            <input  type="button" class="btn btn-primary mb-2" data-dismiss="modal" aria-label="Close" value="No">
                          </div>
               
            </form>
          </div>
      </div>
    </div>
  </div>
</div>


      <!-- Modal -->
<div class="modal fade" id="reviewmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rating & Review Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body newModalCss1">
        <div class="delete_order">
         <form method="post" action=" @if(isset($orderproduct->order_cart_id))  {{url('/order_review/'.$orderproduct->order_cart_id)}} @endif" enctype="multipart/form-data">
          {{csrf_field()}}
          <div class="form-group">
            <p>Rating :</p>
              <div class="rating"> 
                <input type="radio" name="rating" value="5" id="5" required=""><label for="5">☆</label> 
                <input type="radio" name="rating" value="4" id="4" required=""><label for="4">☆</label> 
                <input type="radio" name="rating" value="3" id="3" required=""><label for="3">☆</label> 
                <input type="radio" name="rating" value="2" id="2" required=""><label for="2">☆</label> 
                <input type="radio" name="rating" value="1" id="1" required=""><label for="1">☆</label>
              </div>

            </p>
            <label for="exampleFormControlTextarea1">Write comment for order : </label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="order_review"></textarea>
          </div>
          <div class="form-group" style="text-align: center;">
            <input  type="hidden"  name="user_id" value="{{$orderid->user_id}}">
            <input  type="submit" class="btn btn-primary mb-2" name="submit" value="Yes" onclick="submitReview()">
            <input  type="button" class="btn btn-primary mb-2" data-dismiss="modal" aria-label="Close" value="No">
          </div>

        </form>
      </div>
      </div>
    </div>
  </div>
</div>


      <script type="text/javascript">


        function submitReview() {
          var ratingSelect = $("input[name='rating']:checked").val();
          if(!ratingSelect){
            swal({
             title: "",
             text: "Please select rating",
             icon:"info",
             //timer: 1000,
             buttons: 'OK'
           });
          }

          console.log(ratingSelect);      
        }


        
        function delete_order(cartid){
            //to sure to delete beg
            swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this imaginary file!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    // swal("Poof! Your imaginary file has been deleted!", {
    //   icon: "success",
    // });
    //add code to show pop up form for cancel reason and image  beg
      $('.delete_order').show();
    //add code to show pop up form for cancel reason and image  end
  } else {
    swal("Your imaginary file is safe!");
  }
});
            //to sure to delete end
        }
  //cancel specific item from ordr
  function cancel_item($cartid,varientid){
    alert($cartid+' '+varientid);

  }
  //cancel specific item from ordr


      </script>
@endsection