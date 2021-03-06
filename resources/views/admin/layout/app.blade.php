<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="{{url($logo->favicon)}}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    {{$logo->name}}-Admin
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="{{url('assets/css/material-dashboard.css?v=2.1.2')}}" rel="stylesheet" />
  <link href="{{url('assets/css/select2.min.css')}}" rel="stylesheet" />
  <link href="{{url('assets/css/select2-bootstrap.css')}}" rel="stylesheet" />
  <link href="{{url('assets/css/custom.alobha.css')}}" rel="stylesheet" />
  <style>
      .card-header.card-header-primary {
    padding: 10px !important;
    }
    .alert {
    padding: 6px !important;
    }
  </style>
</head>

<body class="">
  <div class="wrapper ">
    <!--sidebar-->
     @include('admin.layout.sidebar')
    <div class="main-panel">
      <!-- Navbar -->
      @include('admin.layout.nav')
      <!-- End Navbar -->
      <div class="content">
       @yield('content')
      </div>
        <!--footer-->
        @include('admin.layout.footer')

    </div>

  </div>


</body>
   <!--   Core JS Files   -->
  <script src="{{ url('assets/js/core/jquery.min.js') }}"></script>
  <script src="{{ url('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ url('assets/js/core/bootstrap-material-design.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/moment.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/sweetalert2.js') }}"></script>
  <script src="{{ url('assets/js/plugins/jquery.validate.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/jquery.bootstrap-wizard.js') }}"></script>
  <script src="{{ url('assets/js/plugins/bootstrap-selectpicker.js') }}"></script>
  <script src="{{ url('assets/js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/bootstrap-tagsinput.js') }}"></script>
  <script src="{{ url('assets/js/plugins/jasny-bootstrap.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/fullcalendar.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/jquery-jvectormap.js') }}"></script>
  <script src="{{ url('assets/js/plugins/nouislider.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <script src="{{ url('assets/js/plugins/arrive.min.js') }}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <script src="{{ url('assets/js/plugins/chartist.min.js') }}"></script>
  <script src="{{ url('assets/js/plugins/bootstrap-notify.js') }}"></script>
  <script src="{{ url('assets/js/material-dashboard.js?v=2.1.2') }}" type="text/javascript"></script>
  <script src="{{ url('assets/demo/demo.js') }}"></script>
  <script src="{{ url('assets/js/plugins/select2.min.js') }}"></script>

  @yield('javascript')
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

    });
    
    //datatable initialise
    
    $(function (){
      var table =$('.table').DataTable({
          'paging'      : false,
          'lengthChange': true,
          'searching'   : true,
          'info'        : true,
          'ordering'    : true,
          'autoWidth'   : true,
          "bStateSave"  : true,
          "pageLength": 20,
          language: {
              search: "",
              searchPlaceholder: "Global Search"
          },
            "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('DataTables', JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                return JSON.parse(localStorage.getItem('DataTables'));
            },
          dom: '<"row"<"col-md-6"lB><"col-md-6 text-right"f><"col-md-12"rt><"col-md-6"i><"col-md-6"p>',
            buttons: [
                 'csv', 'excel', 'pdf'
            ]
        })
    });
    //getting the value of search box
    $('input[type="search"]').unbind().keyup(function(e) {
        var value = $(this).val();
        alert(value);
        if (value.length>3) {
            alert(value);
            table.search(value).draw();
        } else {     
            //optional, reset the search if the phrase 
            //is less then 3 characters long
            table.search('').draw();
        }        
    });

    $(function(){
        $(".nav .nav-item a").each(function() {
        var link = $(this);
          if (link.get(0).href === location.href) {
          link.closest('a').addClass('active');
          
          // return false;
        } else {
          link.closest('a').removeClass('active');
        }
      });
    });
  </script>

  <script>
    $(".select2tags").select2({
      tags: true
    });

    // btn-modal.
    $(document).on( 'click', 'button.btn-modal', function(e){
      e.preventDefault();
      var container = $(this).data("container");

      $.ajax({
          url: $(this).data("href"),
          dataType: "html",
          success: function(result){
              $(container).html(result).modal('show');
          }
      });
    });
  
  </script>
</html>