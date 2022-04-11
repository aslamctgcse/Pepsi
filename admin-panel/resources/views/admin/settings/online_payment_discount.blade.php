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
                  <h4 class="card-title">Online Payment Discount Setting</h4>
                </div>
                <form class="forms-sample" action="{{route('updateonline_payment_discount')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <div class="card-body">
                    @foreach($onlinePayment_discount as $onlinePaymentDiscount)
                      <input type="hidden" name="onlineDiscount[{{$loop->iteration}}][online_pay_discount_id]" value="{{ $onlinePaymentDiscount->online_pay_discount_id }}">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Minimum Online Payment Amount*</label>
                            <input type="text" name="onlineDiscount[{{$loop->iteration}}][online_payment_min_amount]" value="{{($onlinePaymentDiscount->online_payment_min_amount)}}" class="form-control" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Maximum Online Payment Amount*</label>
                            <input type="text" name="onlineDiscount[{{$loop->iteration}}][online_payment_max_amount]" value="{{($onlinePaymentDiscount->online_payment_max_amount)}}" class="form-control" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Discount(%)*</label>
                            <input type="text" name="onlineDiscount[{{$loop->iteration}}][online_payment_discount]" value="{{($onlinePaymentDiscount->online_payment_discount)}}" class="form-control" required>
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