@extends('admin.layout.app')
<style>
    .btn{
        height:27px !important;
    }
    .material-icons{
        margin-top:0px !important;
        margin-bottom:0px !important;
    }
</style>
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
    <h4 class="card-title ">Queries</h4>
  </div>
  <table class="table">
      <thead>
          <tr>
              <th class="text-center">#</th>
              <th>User Name</th>
              <th>User Phone</th>
              <th>User Email</th>
              
              <th>Query Message</th>
              <th>Query Date</th>
              <th>View</th>
          </tr>
      </thead>
      <tbody>
             @if(count($queries)>0)
            @php $i=1; @endphp
            @foreach($queries as $query	)
          <tr>
              <td class="text-center">{{$i}}</td>
              <td>{{$query->name}}</td>
              <td>{{$query->mobile}}</td>
              <td>{{$query->email}}</td>
                 
              <td class="td-actions text-left">
                  <!-- {{$query->message}} -->
                  {{Str::words($query->message, $words = 7, $end = '...')}}
              </td>
              <td>{{$query->created_at}}</td>
              <td class="td-actions text-center">
                <a href="{{route('queryDetails',$query->id)}}" rel="tooltip" class="btn btn-primary">
                      <i class="material-icons">visibility</i>
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
<div class="pagination justify-content-end" align="right" style="width:100%;float:right !important">{{-- $queries->links() --}}</div>
</div>
</div>
</div>
</div>
<div>
    </div>
    @endsection
</div>