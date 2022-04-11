@extends('admin.layout.app')
<style>
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="crossorigin="anonymous"></script>
      #map {
        height: 100%;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }
      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
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
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Edit Delivery Boy</h4>
                  <form class="forms-sample" action="{{route('UpdateD_boy', $d_boy->dboy_id)}}" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}
                </div>
                <div class="card-body pt-5">

                    <div class="row">

                      <div class="col-md-4">
                        <div class="form-group bmd-form-group is-filled">
                          <label class="bmd-label-floating">Store Name*</label>
                          <select name="store_id" class="form-control" required="required">
                            <option value="">Select Store</option>
                            @foreach($stores as $store)
                              <option value="{{ $store->store_id }}"{{ ($store->store_id == $d_boy->store_id) ? "selected='selected'" : '' }}>{{ $store->store_name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Delivery Boy Name*</label>
                          <input type="text" name="boy_name" class="form-control" value="{{$d_boy->boy_name}}" required>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group">
                          <label class="bmd-label-floating">Delivery Boy Phone*</label>
                          <input type="text" name="boy_phone" maxlength="10" onkeypress="return numbersonly(event)" class="form-control" value="{{$d_boy->boy_phone}}" required>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Delivery Boy Licence Number*</label>
                          <input type="text" name="boy_licence_number" value="{{$d_boy->boy_licence_number}}" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Delivery Boy Licence Image</label>
                          <div class="row">
                            <div class="col-md-8">
                              <input type="file" name="licence_image" class="form-control-file">
                            </div>
                            <div class="col-md-4">
                              
                          <img src="{{url($d_boy->licence_image)}}" alt="image" name="old_image" style="width:100px;height:100px; border-radius:50%">
                            </div>
                          </div>
                          
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Delivery Boy Identification*</label>
                          <input type="text" name="boy_identification" value="{{ $d_boy->boy_identification }}" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Delivery Boy Identification Image</label>
                          <div class="row">
                            <div class="col-md-8">
                              <input type="file" name="identification_image" class="form-control-file" >
                            </div>
                            <div class="col-md-4">
                              <img src="{{ url($d_boy->identification_image) }}" alt="image" name="old_image" style="width:100px;height:100px; border-radius:50%">
                            </div>
                          </div>
                          
                          
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Medical Data*</label>
                          <input type="text" name="medical_data" value="{{ $d_boy->medical_data }}" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Life Insurence Number*</label>
                          <input type="text" name="life_insurence_number" value="{{ $d_boy->life_insurence_number }}" class="form-control" required>
                        </div>
                      </div>
                    </div>
                     
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Password*</label>
                          <input type="text" name="password" class="form-control" value="{{$d_boy->password}}" required>
                        </div>
                      </div>
                    
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Delivery Boy Address*</label>
                          <input type="text" name="boy_loc" id="autocomplete"  class="form-control" value="{{$d_boy->boy_loc}}" required>
                        </div>
                      </div>
                    </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form">
                            <label> Select City*</label>
                            <select name="city" class="form-control" required>
                                <option>Select City</option>
                                @foreach($city as $cities)
                                <option value="{{$cities->city_name}}" @if($cities->city_name == $d_boy->boy_city) selected @endif>{{$cities->city_name}}</option>
                                @endforeach
                           </select>
                          </div>
                        </div>
                      </div><br>

                    <button type="submit" class="btn btn-primary pull-center">Submit</button>
                    <a href="{{route('d_boylist')}}" class="btn">Close</a>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
			</div>
          </div>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
{{-- javascript code --}}
<script src="https://maps.google.com/maps/api/js?key={{$map}}=places&callback=initAutocomplete" type="text/javascript"></script>
<script>
   $(document).ready(function() {
        $("#lat_area").addClass("d-none");
        $("#long_area").addClass("d-none");
   });
</script>
<script>
   google.maps.event.addDomListener(window, 'load', initialize);

   function initialize() {
       var input = document.getElementById('autocomplete');
       var autocomplete = new google.maps.places.Autocomplete(input);
       autocomplete.addListener('place_changed', function() {
           var place = autocomplete.getPlace();
           $('#latitude').val(place.geometry['location'].lat());
           $('#longitude').val(place.geometry['location'].lng());
           $("#lat_area").removeClass("d-none");
           $("#long_area").removeClass("d-none");
       });
   }

   function numbersonly(e) {
     var k = event ? event.which : window.event.keyCode;
     if (k == 32) return false;
     var unicode=e.charCode? e.charCode : e.keyCode;

                if (unicode!=8) { //if the key isn't the backspace key (which we should allow)
                    if (unicode<48||unicode>57) //if not a number
                    return false //disable key press
                }
              }
</script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{$map}}&libraries=places&callback=initMap"
        async defer></script>  
         
@endsection




