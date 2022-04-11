@extends('store.layout.app')

@section ('content')
 <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats card-c-orange">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">local_atm</i>
                  </div>
                  <h3 class="card-title">{{round($sum,2)}}
                  <p class="card-category">Total Earning</p>
                  </h3>
                </div>
              </div>
            </div>
             <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats card-c-pink">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">local_atm</i>
                  </div>
                  <h3 class="card-title">{{round($paid,2)}}</h3>
                  <p class="card-category">Paid By Admin</p>
                </div>
                 
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats card-c-red">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">layers</i>
                  </div>
                  <h3 class="card-title">{{$pending}}</h3>
                  <p class="card-category">Pending Orders</p>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats card-c-brown">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">info_outline</i>
                  </div>
                  <h3 class="card-title">{{$cancelled}}</h3>
                  <p class="card-category">Cancelled Orders</p>
                </div>
               
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats card-c-blue">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">face</i>
                  </div>
                  <h3 class="card-title">{{$completed_orders}}</h3>
                  <p class="card-category">Completed Orders</p>
                </div>
                
              </div>
            </div>
          </div>
        </div>
@endsection