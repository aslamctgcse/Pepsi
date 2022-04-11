@extends('store.layout.app')

@section ('content')
 <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats card-c-blue">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">local_atm</i>
                  </div>
                  <h3 class="card-title">{{round($sum,2)}} 
                  <p class="card-category">Total Earning</p>
                  </h3>
                </div>
                <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">local_atm</i>
                    <a href="javascript:;">Total Earnings</a>
                  </div>
                </div> -->
              </div>
            </div>
             <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats  card-c-pink ">
                <div class="card-header card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">local_atm</i>
                  </div>
                  <h3 class="card-title">{{round($paid,2)}}
                  <p class="card-category">Paid By Admin</p>
                  </h3>
                </div>
                 <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">local_atm</i>
                    <a href="javascript:;">Paid by admin</a>
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
                  <h3 class="card-title">{{$pending}}
                  <p class="card-category">Pending Orders</p>
                  </h3>
                </div>
                <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">layers</i>
                    <a href="javascript:;">Total Pending Orders</a>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats card-c-orange">
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
                    <a href="javascript:;">Total Cancelled Orders</a>
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
                  <h3 class="card-title">{{$completed_orders}}
                  <p class="card-category">Completed Orders</p>
                  </h3>
                </div>
                 <!-- <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">face</i>
                    <a href="javascript:;">Completed Orders</a>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
@endsection