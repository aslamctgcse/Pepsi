@extends('website.layouts.app')
@section('content')
<style>
	.walletContainer {
    width: 50%;
    margin: auto;
    text-align: center;
}
i.fa.fa-trophy {
    font-size: 150px;
    color: #e96125;
}
p.reward_text, .reward_val {
    font-size: 20px;
}
</style>

	@php
		$user = Session::get('userData');
	@endphp

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
                              	<!-- <img alt="logo" src="{{-- $user->user_image ?? '' --}}" alt="Logo Image"> -->
                              	<!-- <img alt="logo" src="{{ url('assets/website/img/logo.png') }}" alt="Logo Image"> -->
                                <img alt="logo" src="@if(file_exists($user->user_image)){{ url($user->user_image)}} @else {{url('/assets/website/img/dealwy-logo.png')}} @endif" alt="Logo Image">
                              	<h5 class="mb-1 text-secondary">
                              		<strong>Hi </strong> {{ $user->user_name ?? '' }}
                              	</h5>
                              	<p> {{ $user->user_phone ?? '' }} </p>
                           	</div>
                           	<div class="list-group">
                              	<a href="{{url('/my-profile')}}" class="list-group-item list-group-item-action">
                              		<i aria-hidden="true" class="mdi mdi-account-outline"></i>  My Profile
                              	</a>
                              	 <a href="{{url('/my-profile')}}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address</a>

                              	<!-- <a href="{{url('/my-profile')}}" class="list-group-item list-group-item-action">
                              		<i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address
                              	</a> -->

                              	<!-- <a href="wishlist.php" class="list-group-item list-group-item-action">
                              		<i aria-hidden="true" class="mdi mdi-heart-outline"></i>  Wish List 
                              	</a> -->
                              	<a href="{{url('/wallet')}}" class="list-group-item list-group-item-action active"><i class="fa fa-gift"></i>  Wallet </a> 
                                <a href="{{url('/rewards')}}" class="list-group-item list-group-item-action"><i class="fa fa-gift"></i>  Rewards </a> 

                              	<a href="{{url('/orders')}}" class="list-group-item list-group-item-action">
                              		<i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i>  Order List
                              	</a>

                              	<a href="{{ route('user-logout') }}" class="list-group-item list-group-item-action">
                              		<i aria-hidden="true" class="mdi mdi-lock"></i>  Logout
                              	</a> 
                           	</div>
                        </div>
                     </div>
                     <div class="col-md-8">
                        <div class="card card-body account-right">
                           <div class="widget">
                              <div class="section-header">
                                 <h5 class="heading-design-h5">
                                    Wallet
                                 </h5>
                              </div>
                              <!--wallet section beg-->
                               <div class="walletContainer">
                               	<div class="reward_img">
                               		<!-- <i class="fa fa-trophy" aria-hidden="true"></i> -->
                                  <img src="{{url('/images/wallet.png')}}" />

                               	</div>
                               	<div class="reward_point">
                               		<p class="reward_text">My Wallet Amount</p>
                               		<p class="reward_val">₹ @if(isset($wallet->wallet)) {{$wallet->wallet}} @endif</p>

                               	</div>
                                {{-- recharge wallet beg --}}
                                <div class="rechargeWalletContainer">
                                  <form method="post" action="{{url('/wallet-recharge-initiate')}}" style="display:none;" id="rechargeform">
                                    {{csrf_field()}}
                                    <input type="hidden" name="userid" value="{{$user_session_data->user_id}}" />
                                    <div class="row">
                                    <div class="col-sm-12">
                                       <div class="form-group">
                                         
                                          <input class="form-control border-form-control" placeholder="Enter Amount" type="text" value="" name="rechargeAmount" required="" onkeypress="return numonly(event)">
                                       </div>
                                    </div>
                                    
                                 </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <div class="form-group">
                                         
                                          <input class="btn btn-info"  type="submit"  name="submit" value="Recharge">
                                       </div>
                                    </div>
                                    
                                 </div>
                                  </form>
                                  {{-- recharge button --}}
                                    <button type="button" class="btn btn-info" onclick="showRechargeForm()" id="rechargebtn">Recharge</button>
                                  {{-- recharge button --}}
                                </div>
                              
                                {{-- recharge wallet end --}}
                               </div>
                              <!--wallet section end-->

                           </div>
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
    <script type="text/javascript">
      function showRechargeForm(){
        $('#rechargeform').show();
        $('#rechargebtn').hide();
      }
    </script>
@stop