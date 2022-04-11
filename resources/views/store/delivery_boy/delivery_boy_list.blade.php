@extends('store.layout.app')
<style>
    .collo {
      overflow-y: hidden;
      overflow-x: scroll;
      -webkit-overflow-scrolling: touch;
    }

</style>
@section ('content')
    <div class="container-fluid">
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

      <div class="row collo">
        <div class="col-md-12">
          <div class="card"> 
            <div class="card-header card-header-primary">
              <h4 class="card-title ">Delivery Boy List</h4>
            </div>

            <table class="table">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Boy Name</th>
                  <th>Boy Phone</th>
                  <th>Boy Password</th>
                  <th>Boy Store Name</th>
                  <th>Is Engaged</th>
                  <th>Status</th>
                  <th>Orders</th>
                </tr>
              </thead>
              <tbody>
                  @if(count($dBoys) > 0)
                    @php $i = 1; @endphp
                    @foreach($dBoys as $dBoy)
                      <tr>
                        <td class="text-center">{{$i}}</td>
                        <td>{{$dBoy->boy_name}}</td>
                        <td>{{$dBoy->boy_phone}}</td>
                        <td>{{$dBoy->password}}</td>
                        <td>{{$dBoy->store_name}}</td>
                        @if($dBoy->is_engaged == 1)
                          <td><p style="color:green">Engaged</p></td>
                        @else
                          <td><p style="color:red">Not Engaged</p></td>
                        @endif

                        @if($dBoy->status == 1)
                          <td><p style="color:green">on duty</p></td>
                        @else
                          <td><p style="color:red">off duty</p></td>
                        @endif
                        <td>
                          <a href="{{route('delivery_boy_orders',$dBoy->dboy_id)}}" rel="tooltip" class="">
                            <i class="material-icons">layers</i>
                          </a>
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

            <div class="pagination justify-content-end" align="right" style="width:100%;float:right !important">{{ $dBoys->links() }}</div>
          </div>
        </div>
      </div>
    </div>
@endsection
</div>