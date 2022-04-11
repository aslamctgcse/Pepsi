@extends('admin.layout.app')
<style>

    .blockCss{
        border-bottom: 1px solid #e6e3e3;
        margin-bottom: 15px;
    }
    .blockCss p{
        margin-bottom: 3px;
    }
</style>

@section ('content')
<div class="container-fluid">
          <div class="row">

            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Query</h4>
                  
                </div>


            @if(count($details)>0)
              @php $i=1; @endphp
                            
              @foreach($details as $detailss)

                <div class="card-body">
                  <form class="forms-sample" >
                    <div class="row">
                      <div class="col-md-12">
                        <div class="blockCss">
                          <label class="">User Name</label>
                          
                          <p >{{$detailss->name}}</p>
                        </div>
                      </div>  

                      <div class="col-md-12">
                        <div class="blockCss">
                          <label class="">Mobile</label>
                          
                          <p >{{$detailss->mobile}}</p>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="blockCss">
                          <label class="">Email</label>
                          
                          <p >{{$detailss->email}}</p>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="blockCss">
                          <label class="">Message</label>
                          
                          <p >{{$detailss->message}}</p>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="blockCss">
                          <label class="">Submitted Date</label>
                          
                          <p >{{$detailss->created_at}}</p>
                        </div>
                      </div>



                      </div>

                    <div class="clearfix"></div>
                  </form>
                </div>

                @php $i++; @endphp
              @endforeach
            @else
                <div>
                  No data found
                </div>
            @endif

              </div>
            </div>
      </div>
          </div>
@endsection          