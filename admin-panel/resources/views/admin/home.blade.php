@extends('admin.layout.app')

@section ('content')
 <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats card-c-orange">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">people</i>
                  </div>
                  <h3 class="card-title">{{$app_users}}
                  <p class="card-category">Total Users</p>
                    <!-- <small>users</small> -->
                  </h3>
                </div>
                <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">people</i>
                    <a href="{{route('userlist')}}">Total App Users</a>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats  card-c-pink ">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">store</i>
                  </div>
                  <h3 class="card-title">{{$stores}}
                  <p class="card-category">Total Stores</p>
                  </h3>
                </div>
                <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">store</i>
                    <a href="{{route('storeclist')}}">Total Stores</a>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats  card-c-red">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">home_work</i>
                  </div>
                  <h3 class="card-title">{{$city}}
                  <p class="card-category">Cities</p>
                  </h3>
                </div>
               <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">home_work</i>
                    <a href="{{route('citylist')}}">Total Cities</a>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats  card-c-brown">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">face</i>
                  </div>
                  <h3 class="card-title">{{$delivery_boys}}
                  <p class="card-category">Delivery Boys</p>
                  </h3>
                </div>
                 <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">face</i>
                    <a href="{{route('d_boylist')}}">Total Delivery Boys</a>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
         <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats  card-c-cyan">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">local_atm</i>
                  </div>
                  <h3 class="card-title">{{round($total_earnings,2)}} 
                  <p class="card-category">Total Earning</p>
                  </h3>
                </div>
                <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">local_atm</i>
                    <a href="{{route('finance')}}">All Stores Earnings</a>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats  card-c-darkpink">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">layers</i>
                  </div>
                  <h3 class="card-title">{{$pending}}
                  <p class="card-category">Pending Orders</p>
                  </h3>
                </div>
                <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">layers</i>
                    <a href="{{route('admin_pen_orders')}}">Total Pending Orders</a>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats  card-c-green">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">layers</i>
                  </div>
                  <h3 class="card-title">{{$cancelled}}
                  <p class="card-category">Cancelled Orders</p>
                  </h3>
                </div>
               <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">info_outline</i>
                    <a href="{{route('admin_can_orders')}}">Total Cancelled Orders</a>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats  card-c-blue">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">layers</i>
                  </div>
                  <h3 class="card-title">{{$completed_orders}}
                  <p class="card-category">Completed Orders</p>
                  </h3>
                </div>
                 <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">face</i>
                    <a href="{{route('admin_com_orders')}}">Completed Orders</a>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
@endsection