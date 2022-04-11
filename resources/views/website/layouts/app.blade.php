</!DOCTYPE html>
<?php

 ?>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <!-- <title>Daily Needs Product</title> -->
      <!--added favicon beg-->
      <link rel="shortcut icon" href="{{ url('assets/website/img/dealwy-fevicon.png') }}" type="image/x-icon">
      <!--added favicon end-->
      <title>Dealwy Website</title>
      {{--- CSS Include ---}}
      @include('website.layouts.css')

      {{--- Define CSS Yield ---}}
      @yield('css')
   </head>
   <body>

      {{--- HEADER Include ---}}
      @include('website.layouts.header')

      {{--- Define content Yield ---}}
      @yield('content')

      {{--- FOOTER Include ---}}
      @include('website.layouts.footer')

      {{--- JS Include ---}}
      @include('website.layouts.js')

      {{--- Define JS Yield ---}}
      @yield('java-script')

      <div class="modal fade login_register" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
      </div>
<!-- terms and conditions popup -->
   <div class="modal " id="termcondition" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header border-none pb-0">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body pt-0">
               <h5 class="text-center text-pink">Terms & Conditions</h5>
              <!--  <p class="text-justify">Personal Information SFS Daily Needs Pvt Ltd (“SFS”) is the licensed owner of the brand SFS daily Needs and the website Dailyneeds.com (”The Site”) from Supermarket Grocery Supplies Pvt Ltd . SFS respects your privacy. This Privacy Policy provides succinctly the manner your data is collected and used by SFS on the Site. As a visitor to the Site/ Customer, you are advised to please read the Privacy Policy carefully. By accessing the services provided by the Site you agree to the collection and use of your data by SFS in the manner provided in this Privacy Policy.</p> -->
              <p class="text-justify"> {!! substr($terms->description ?? '',0,695) !!}...<a href="{{url('term-and-condition')}}" class="color-theme">Read more</a></p>
               
               <div class="form-group">
                  <div class="d-flex align-items-center">
                     <label class="checkbox-container">
                        <input class="checkbox2 checkbox_class" type="checkbox"> <span class="checkmark"></span>
                     </label> <span class="ml-1 text-pink" data-toggle="modal" data-target="#termcondition">I accept and authorize the terms and conditions.</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
<!--term and condtion-->
   </body>
</html>