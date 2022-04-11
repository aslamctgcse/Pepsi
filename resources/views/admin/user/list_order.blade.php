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
        <div class="col-lg-12">
          <div class="card">    
            <div class="card-header card-header-primary">
              <h4 class="card-title ">Customer Orders</h4>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Order Id</th>
                        <th>Price</th>
                        <th>User</th>
                        <th>Store</th>
                        <th>Delivery boy</th>
                        <th>Delivery Date</th>
                        <th>Cancelling Reason</th>
                        <th>Cart Products</th>
                    </tr>
                </thead>
                <tbody>
                       @if(count($orders)>0)
                      @php $i=1; @endphp
                      @foreach($orders as $ords)
                    <tr>
                        <td class="text-center">{{$i}}</td>
                        <td>{{$ords->cart_id}}</td>
                        <td>{{$ords->total_price}}</td>
                        <td>{{$ords->user_name}}<p style="font-size:14px">({{$ords->user_phone}})</p></td>
                        <td>{{$ords->store_name}}<p style="font-size:14px">({{$ords->phone_number}})</p></td>
                        <td>{{$ords->boy_name}}<p style="font-size:14px">({{$ords->boy_phone}})</p></td>
                         <td>{{$ords->delivery_date}}</td>
                         <td>{{$ords->cancelling_reason}}</td>
                        <td>
                          <button type="button" class="btn btn-primary btn-modal" 
                                  data-href="{{ route('userOrdersDetails', [$ords->cart_id]) }}" 
                                  data-container=".details"> 
                            Details
                          </button>
                        </td>
                    </tr>
                      @php $i++; @endphp
                   @endforeach
                    @else
                      <tr>
                        <td>No data found</td>
                      </tr>
                    @endif
                </tbody>
            </table>
            <div class="pagination justify-content-end" align="right" style="width:100%;float:right !important">{{ $orders->links() }}</div>
</div>
</div>
</div>
</div>
<div>
    </div>



  <div class="modal details" data-keyboard="false" tabindex="-1" role="dialog" 
      aria-labelledby="gridSystemModalLabel">
  </div>

  @endsection
</div>