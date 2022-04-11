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
                  <h4 class="card-title">Bulk Order Discount Setting</h4>
                </div>
                <form class="forms-sample" action="{{route('updatebulk_order_discount')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="card-body">
                    @foreach($bulkOrder_discount as $bulkOrderDiscount)
                      <input type="hidden" name="bulkDiscount[{{$loop->iteration}}][bulk_discount_id]" value="{{ $bulkOrderDiscount->bulk_discount_id }}">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Minimum Bulk Order Value*</label>
                            <input type="text" name="bulkDiscount[{{$loop->iteration}}][bulk_order_min_amount]" value="{{($bulkOrderDiscount->bulk_order_min_amount)}}" class="form-control" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Maximum Bulk Order Value*</label>
                            <input type="text" name="bulkDiscount[{{$loop->iteration}}][bulk_order_max_amount]" value="{{($bulkOrderDiscount->bulk_order_max_amount)}}" class="form-control" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Discount(%)*</label>
                            <input type="text" name="bulkDiscount[{{$loop->iteration}}][bulk_order_discount]" value="{{($bulkOrderDiscount->bulk_order_discount)}}" class="form-control" required>
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