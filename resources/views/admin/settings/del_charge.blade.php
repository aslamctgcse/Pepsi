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
                  <h4 class="card-title">Delivery Charge Setting</h4>
                </div>
                <form class="forms-sample" action="{{route('updatedel_charge')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="card-body">
                    @foreach($del_charge as $delCharge)
                      <input type="hidden" name="delCharge[{{$loop->iteration}}][del_charge_id]" value="{{ $delCharge->id }}">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Minimum Cart Value (minimum free delivery cart value)*</label>
                            <input type="text" name="delCharge[{{$loop->iteration}}][min_cart_value]" value="{{($delCharge->min_cart_value)}}" class="form-control" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Delivery Charge*</label>
                            <input type="text" name="delCharge[{{$loop->iteration}}][del_charge]" value="{{($delCharge->del_charge)}}" class="form-control" required>
                          </div>
                        </div>
                      </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary pull-center">Update</button>
                    <div class="clearfix"></div>
                  </div>
                </form>
              </div>
            </div>
           </div>
          </div>
@endsection