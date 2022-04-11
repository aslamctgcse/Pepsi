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
                  <h4 class="card-title">Update Banner</h4>
                  
                </div>
                <div class="card-body">
                  <form class="forms-sample" action="{{route('bannerupdate',$city->banner_id)}}" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Slider Name*</label>
                          
                          <input type="text" name="banner" value="{{$city->banner_name}}" class="form-control" required>
                        </div>
                        
                        
                         <img src="{{url($city->banner_image)}}" alt="image" name="" style="width:100px;height:100px; border-radius:50%">
                     </div>
                      <div class="col-md-12">
                        <div class="form">
                          <label class="bmd-label-floating">Banner Image</label>
                          <input type="hidden" name="old_image" value="{{$city->banner_image}}" accept=" image/*">
                          <input type="file"name="image" class="form-control" accept=" image/*">
                          
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Category</label>
                          <select name="parent_cat_id" class="form-control catsearch">
                              <option value="0">Select Category</option>
                              @foreach($category as $categorys)
                        <option value="{{$categorys->cat_id}}"  {{(isset($city->category_id) && $city->category_id == $categorys->cat_id) ? 'selected' : '' }}>{{$categorys->title}}</option>
                          @endforeach
                              
                          </select>
                        </div>
                      </div>

                    </div>

                    

                    <button type="submit" class="btn btn-primary pull-center">Update Profile</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
			</div>
          </div>
@endsection          