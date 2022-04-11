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
     <a href="{{route('AddCategory')}}" class="btn btn-primary ml-auto" style="float:right;padding-top: 3px ;"><i class="material-icons">add</i>Add Category</a>
   </div> 
<div class="col-lg-12">
<div class="card">    
<div class="card-header card-header-primary">
      <h4 class="card-title ">Parent Category List</h4>
    </div>
<table class="table">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Category Name</th>
            <!-- <th>Parent Category</th> -->
            <th>Category image</th>
            <th class="text-center">Active/Blocked</th>
            <th class="text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(count($parentcategory)>0)
        @php $i=1; @endphp
        @foreach($parentcategory as $cat)
        <tr>
            <td class="text-center">{{$i}}</td>
           <!--  <td>{{$cat->title}}</td> -->
            <td><a href="{{url('subcategory/list/'.$cat->cat_id)}}">{{$cat->title}}</a></td>
           <!--  @if($cat->parent == 0)
              <td>-------</td>
            @endif
            @if($cat->parent != 0)
              <td>{{$cat->tttt}}</td>
            @endif -->

            <td><a href="{{url('subcategory/list/'.$cat->cat_id)}}">
              <img src="{{url($cat->image)}}" alt="category image" style="width:50px; height:50px; border-radius:50%;"/>
            </a>

            </td>

            <td class="td-actions text-center">
                @if($cat->status == 0)
                  <a href="{{ route('categoryBlock',$cat->cat_id) }}" rel="tooltip" class="btn btn-danger">
                      <i class="material-icons">block</i> Blocked
                  </a>
                @else
                  <a href="{{ route('categoryUnblock',$cat->cat_id) }}" style="padding-top: 3px;" rel="tooltip" class="btn btn-primary">
                    <i class="material-icons">check</i> Active
                  </a>
                @endif
            </td>

            <td class="td-actions text-right">
                <a href="{{route('EditCategory',$cat->cat_id)}}" rel="tooltip" class="btn btn-success">
                    <i class="material-icons">edit</i>
                </a>
               <a href="{{route('DeleteCategory',$cat->cat_id)}}" rel="tooltip" data-name="parent category" class="btn btn-danger delete-confirm-event">
                    <i class="material-icons">close</i>
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
<div class="pagination justify-content-end" align="right" style="width:100%;float:right !important">{{-- $parentcategory->links() --}}</div>
</div>

</div>
</div>
</div>
<div>
    </div>
    @endsection
</div>