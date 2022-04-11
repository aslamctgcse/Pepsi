
@extends('website.layouts.app')
@section('content')
 @if(session()->has('userData'))
                           
<?php
    //dd('azwar');
    $username='';
    $usermobile='';
 $ct= session()->get('userData'); //->user_name;
// dd($ct);
    $username=ucfirst($ct->user_name);
    $usermobile=ucfirst($ct->user_phone);
                             ?>
 @endif
 @if(session()->has('msg'))
    <div class="alert alert-success" style="text-align:center;">
        {{ session()->get('msg') }}
    </div>
@endif

	<section class="account-page section-padding">

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
                              <!-- <a href="wishlist.php" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-heart-outline"></i>  Rewards </a> <i class="fas fa-gift"></i>-->

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

                                    Sub Order List (Order ID : {{$profile_main_orders->cart_id}})

                                 </h5>

                              </div>

                              <div class="order-list-tabel-main table-responsive">

                                 <table class="datatabel table table-striped table-bordered order-list-tabel" width="100%" cellspacing="0">

                                    <thead>

                                       <tr>

                                          <th>Sub Order #</th>

                                          <th>Date Purchased</th>

                                          <th>Status</th>

                                          <th>Total</th>

                                          <th>Action</th>

                                       </tr>

                                    </thead>

                                    <tbody>
                                       @foreach($profile_sub_orders as $profile_order)
                                       <?php //dd($profile_orders);
                                       $i=0;
                                        ?>

                                       <tr>

                                          <td>#{{$profile_order->sub_order_id}}</td>
                                          

                                          <td>{{$profile_order->order_date}}</td>
                                          <?php 
                                          $addclass='';
                                          $addtext='';
                                           if(empty($profile_order->order_status)){
                                             $addclass='badge badge-info';
                                             $addtext='In Progress';
                                           }

                                            if(!empty($profile_order->order_status) && $profile_order->order_status == 'confirmed' || $profile_order->order_status == 'Confirmed'){
                                             $addclass='btn btn-secondary';
                                             $addtext='Confirmed';
                                           }
                                           if(!empty($profile_order->order_status) && $profile_order->order_status == 'pending' || $profile_order->order_status == 'Pending'){
                                             $addclass='badge badge-danger';
                                             $addtext='Pending';
                                           }
                                           if(!empty($profile_order->order_status) && $profile_order->order_status == 'Cancelled'  || $profile_order->order_status == 'cancelled'){
                                             $addclass='badge badge-danger';
                                             $addtext='Cancelled';
                                           }
                                           /*if(!empty($profile_order->order_status) && $profile_order->order_status == 'delayed'){
                                             $addclass='badge badge-warning';
                                             $addtext='Delayed';
                                           }
                                           if(!empty($profile_order->order_status) && $profile_order->order_status == 'delivered'){
                                             $addclass='badge badge-success';
                                             $addtext='Delivered';
                                           }*/
                                           #status for out for delivery beg
                                           if(!empty($profile_order->order_status) && $profile_order->order_status == 'Out_For_Delivery'){
                                             $addclass='badge badge-success';
                                             $addtext='Ready For Pickup';
                                           }
                                           #status for out for delivery end
                                           #status for completed i.e is delivered order beg
                                           if(!empty($profile_order->order_status) && $profile_order->order_status == 'Completed' || $profile_order->order_status == 'completed'){
                                             $addclass='badge badge-success';
                                             $addtext='Completed';
                                           }
                                           #status for completed i.e is delivered order end
                                          ?>

                                          <td><span class="{{$addclass}}">{{$addtext}}</span></td>

                                          <td>
                                            @if($profile_order->total_price>0)
                                            ₹{{$profile_order->total_price}}
                                            @else
                                             ₹{{$profile_order->price_without_delivery}}
                                            @endif
                                          </td>

                                          <td>

                                             @if($profile_order->order_status == 'Cancelled' || $profile_order->order_status == 'Confirmed' || $profile_order->order_status == 'confirmed')
                                              <a href="{{url('/reorder/'.$profile_order->cart_id.'/'.$profile_order->sub_order_id)}}" class="btn btn-success">Reorder</a>
                                              <br>
                                              <!-- url('/order-detail/'.$profile_order->cart_id -->
                                              <a data-toggle="tooltip" data-placement="top" title="" href="{{url('/sub-order-detail/'.$profile_order->sub_order_cart_id)}}" data-original-title="View Detail" class="btn btn-info btn-sm" style="margin-top: 10px;"><i class="mdi mdi-eye"></i></a>
                                             @else
                                             <a data-toggle="tooltip" data-placement="top" title="" href="{{url('/sub-order-detail/'.$profile_order->sub_order_cart_id)}}" data-original-title="View Detail" class="btn btn-info btn-sm"><i class="mdi mdi-eye"></i></a>
                                             @endif
                                            

                                          </td>

                                       </tr>
                                       @endforeach
                                       <tr role="row" style="">

                                       <th colspan="4" style="text-align:right;border-bottom: 1px solid #dedede;">Order Total </th>
                                          <!-- <th colspan="">{{-- $ordertotal --}}</th> -->
                                          <th colspan="1" style="border-bottom:1px solid #dedede;">₹{{$profile_main_orders->total_price}}</th>
                                       </tr>

                                       

                                    </tbody>

                                 </table>

                              </div>

                           </div>

                        </div>

                     </div>

                  </div>

               </div>

            </div>

         </div>

      </section>
@endsection