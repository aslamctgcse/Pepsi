
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Order Details (<b>{{ $cart_id }}</b>)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
         <!--//form-->
        <table class="table table-bordered" id="example2" width="100%" cellspacing="0">
          <thead>
            <tr>
            <th>product details</th>
            <th>order qty</th>
            <th>Price</th>
            </tr>
          </thead>
          <tbody>
            @if(count($details)>0)
              @php $i=1; @endphp
                            
              @foreach($details as $detailss)
                <tr>         
                    <td><p><img style="width:25px;height:25px; border-radius:50%" src="{{url($detailss->varient_image)}}" alt="$detailss->product_name">  {{$detailss->product_name}}({{$detailss->quantity}}{{$detailss->unit}})</p>
                    </td>
                    <td>{{$detailss->qty}}</td>
                    <td> 
                    <p><span style="color:grey">{{$detailss->price}}</span></p>
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
        <!--//form-->
    </div>
  </div>
