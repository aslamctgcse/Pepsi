@extends('website.layouts.app')
@section('content')

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
                        <div class="card account-left" style="height: 100%">
                           	<div class="user-profile-header">
                              	<!-- <img alt="logo" src="{{-- $user->user_image ?? '' --}}" alt="Logo Image"> -->
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
                              	 <a href="{{url('/my-address/'.$user->user_id)}}" class="list-group-item list-group-item-action active"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address</a>

                              	<!-- <a href="{{url('/my-profile')}}" class="list-group-item list-group-item-action">
                              		<i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address
                              	</a> -->

                              	<!-- <a href="wishlist.php" class="list-group-item list-group-item-action">
                              		<i aria-hidden="true" class="mdi mdi-heart-outline"></i>  Wish List 
                              	</a> -->

                                <!-- //comment till pending work -->
                              	<!-- <a href="{{url('/wallet')}}" class="list-group-item list-group-item-action"><i class="fa fa-gift"></i>  Wallet </a> 
                              	<a href="{{url('/rewards')}}" class="list-group-item list-group-item-action"><i class="fa fa-gift"></i>  Rewards </a>  -->

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
                                    My Address
                                 </h5>
                              </div>
                              <form method="post" action="{{url('my-address/'.$user->user_id)}}">
                              	{{csrf_field()}}
                                 <div class="row">
                                   
                                 
                                 </div>
                                
                                 <div class="row">
                                   

                                    <div class="col-sm-12">
                                       	<div class="form-group">
                                          	<label class="control-label">City <span class="required">*</span></label>
                                          	<select  class="select2 form-control border-form-control" name="user_city" required>
                                            	<option value="">Select City</option>
                                            	<!-- <option value="Noida" {{(!empty($profileaddress->city) && $profileaddress->city == 'Noida') ? 'selected' : ''}}>Noida</option>
                                            	<option value="Greater Noida" {{(isset($profileaddress->city) && $profileaddress->city == 'Greater Noida') ? 'selected' : '' }}>Greater Noida</option> -->

                                              @foreach($get_city as $getCity)
                                              @if($getCity)
                                              <option value="{{$getCity->city_name}}" {{(isset($profileaddress->city) && $profileaddress->city == $getCity->city_name) ? 'selected' : '' }}>{{$getCity->city_name}}</option>


                                              @endif
                                              @endforeach
                                            	
                                          	</select>
                                       	</div>
                                    </div>
                                 </div>
                                 <div class="row">
                                   <div class="col-sm-12">
                                       <div class="form-group">
                                            <label class="control-label">GST Number</label>
                                            <input class="form-control border-form-control" placeholder="Enter GST Number" type="text" value="{{ $user->user_gst_number ?? '' }}" maxlength="15" minlength="15" name="gst_number">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <div class="form-group">
                                            <label class="control-label">Landmark <span class="required">*</span></label>
                                            <input class="form-control border-form-control" name="user_landmark" value="{{ $profileaddress->landmark ?? '' }}" required  maxlength="80">
                                            
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <div class="form-group">
                                          	<label class="control-label">Address <span class="required">*</span></label>
                                          	<textarea class="form-control border-form-control" name="user_address" required>{{$profileaddress->society ?? ''}}</textarea>
                                          	
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-sm-12">
                                       <div class="form-group">
                                            <label class="control-label">Zip Code <span class="required">*</span></label>
                                            <input class="form-control border-form-control" placeholder="123456" type="text" name="user_zip" value="{{ $profileaddress->pincode ?? '' }}" required minlength="6" maxlength="6" onkeypress="return numonly(event)">
                                       </div>
                                    </div>

                                 </div>
                                 <div class="row">
                                    <div class="col-sm-12 text-right">
                                       <!-- 	<button type="button" class="btn btn-danger btn-lg"> Cencel </button> -->
                                       	<!-- <button type="button" class="btn btn-success btn-lg"> Save Changes </button> -->
                                       	<input type="submit" name="save" class="btn btn-success btn-lg" value="Save Changes" /> 
                                    </div>
                                 </div>
                              </form>
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
@stop