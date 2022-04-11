<!-- Bootstrap core JavaScript -->
<script src="{{ url('assets/website/js/jquery.min.js') }}"></script>
<script src="{{ url('assets/website/js/bootstrap.bundle.min.js') }}"></script>

<!-- select2 Js -->
<script src="{{ url('assets/website/js/select2.min.js') }}"></script>

<!-- Owl Carousel -->
<script src="{{ url('assets/website/js/owl.carousel.js') }}"></script>

<!-- Datatables -->
<script src="{{ url('assets/website/js/datatables.min.js') }}"></script>

<!-- Custom -->
<script src="{{ url('assets/website/js/custom.js') }}"></script>
<script src="{{-- url('assets/website/js/contact_me.js') --}}"></script>
<script src="{{-- url('assets/website/js/jqBootstrapValidation.js') --}}"></script>

<script>
   $(function(){
      $('.navbar-toggler').on('click', function(){
         $('.osahan-menu-2').toggleClass('left-0');
         $('#overlay').css("display", "block");
      });
   });

   $(function(){
      $('.close-sidebar').on('click', function(){
         $('.osahan-menu-2').toggleClass('left-0');
         $('#overlay').css("display", "none");
      });
   });

  // Link Modal.
  $(document).on('click', 'a.link-modal', function(e) {
      e.preventDefault();
      var container = $(this).data("container");

      $.ajax({
        url      : $(this).data("href"),
        dataType : "html",
        success  : function(result) {
          
          $('div.'+container).html(result).modal('show');
        }
      });
  });
  
  // CSRF Token.
  $(document).ready(function(){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
  });

  $(document).on('click', 'button#login-submit', function(event) {
      event.preventDefault();

      $.ajax({
        url      : $("form#login-form").attr("action"),
        method   : 'POST',
        data     : $("form#login-form").serialize(),
        dataType : "json",
        success  : function(result) {
            if (result.success == 200) {
              $('#login-msg').text(result.message);
              window.location.href = result.redirect;
            } else {
              $('#login-msg').text(result.message);
            }
        }
      });
  });

  $(document).on('click', 'button#register-submit', function(event) {
      event.preventDefault();

      $.ajax({
        url      : $("form#register-form").attr("action"),
        method   : 'POST',
        data     : $("form#register-form").serialize(),
        dataType : "json",
        success  : function(result) {
            if (result.success == 200) {
              $('#register-msg').text(result.message);
              window.location.href = result.redirect;
            } else {
              $('#register-msg').text(result.message);
            }
        }
      });
  });

</script>