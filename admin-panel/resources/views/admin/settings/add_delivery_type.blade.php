@extends('admin.layout.app')

@section ('content')
 <div class="container-fluid">
          <div class="row">
               <div class="col-lg-12">
                @if (session()->has('success'))
               <div class="alert alert-success">
                @if(is_array(session()->get('success')))
                        <ul>
                            @foreach (session()->get('success') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                        @else
                            {{ session()->get('success') }}
                        @endif
                    </div>
                @endif
                 @if (count($errors) > 0)
                  @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                      {{$errors->first()}}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                    </div>
                  @endif
                @endif
                </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Add Delivery Type</h4>
                  <form class="forms-sample" action="{{route('AddNewDeliveryType')}}" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}
                </div>
                <div class="card-body">


 
                     <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Type*</label>
                          <input type="text" name="del_type_name" maxlength="40" class="form-control" required>
                        </div>
                      </div>

                    
                      <div class="col-md-6">

                      </div>

                    </div>
                       <!-- <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Time*</label>
                          <input type="text"  name="del_type_time" class="form-control" maxlength="15" required>
                        </div>
                      </div>

                      <div class="col-md-6">
   
                      </div>
                    </div> -->
                      <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Fee*</label>
                          <input type="text"  name="fee_amount" class="form-control" maxlength="3" required>
                        </div>
                      </div>

                      <div class="col-md-6">
   
                      </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary pull-center">Submit</button>
                    <a href="{{route('deliveryType')}}" class="btn">Close</a>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
			</div>
          </div>
@endsection




