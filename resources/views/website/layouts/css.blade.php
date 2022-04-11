<!-- Favicon Icon -->
<link rel="icon" type="image/png" href="">

<!-- Bootstrap core CSS -->
<link href="{{ url('assets/website/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Material Design Icons -->
<link href="{{ url('assets/website/css/materialdesignicons.min.css') }}" rel="stylesheet">

<!-- Select2 CSS -->
<link href="{{ url('assets/website/css/select2-bootstrap.css') }}" rel="stylesheet">
<link href="{{ url('assets/website/css/select2.min.css') }}" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="{{ url('assets/website/css/osahan.css') }}" rel="stylesheet">
<link href="{{ url('assets/website/css/custom.css') }}" rel="stylesheet">
<link href="{{ url('assets/website/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ url('assets/website/css/fonts/maven.css') }}" rel="stylesheet">

<!-- Owl Carousel -->
<link href="{{ url('assets/website/css/owl.carousel.css') }}" rel="stylesheet">
<link href="{{ url('assets/website/css/owl.theme.css') }}" rel="stylesheet">

<!-- datatables -->
<link href="{{ url('assets/website/css/datatables.min.css') }}" rel="stylesheet">

<style>
	.list-group-item.active{
		z-index: 1;
	}
	.osahan-menu-2::-webkit-scrollbar-track
		{
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
			background-color: #2d78bb;
		}

		.osahan-menu-2::-webkit-scrollbar
		{
			width: 3px;
			background-color: #2d78bb;
		}

		.osahan-menu-2::-webkit-scrollbar-thumb
		{
			background-color: #e96125;
		}
	div#navbarNavDropdown{
		justify-content: space-around;
		align-items: baseline;
	}
	a.btn-category:focus{
		box-shadow: none !important;
	}
	
	
		.all-category .btn-category{
		width: auto;
    margin-right: 10px;
    height: 38px;
    padding: 0 5px;
    line-height: 38px;
   background: #778be0  !important;
    color: #fff;
	}
	.all-category .dropdown-menu{
		position: absolute;
	}
	.all-category .dropdown-menu .dropdown-toggle:after{
		border-top: .3em solid transparent;
	    border-right: 0;
	    border-bottom: .3em solid transparent;
	    border-left: .3em solid;
	}
	.all-category .dropdown-menu .dropdown-menu{
		margin-left:0; margin-right: 0;
	}
	.all-category .dropdown-menu li{
		position: relative;
	}
	.all-category .nav-item .submenu{ 
		display: none;
		position: absolute;
		left:100%; top:-7px;
	}
	.all-category .nav-item .submenu-left{ 
		right:100%; left:auto;
	}
	.all-category .dropdown-menu > li:hover{ background-color: #f1f1f1 }
	.all-category .dropdown-menu > li:hover > .submenu{
		display: block;
	    /* position: absolute; */
	    top: 0;
	    left: 100%;
	}

	/*9-4-2021*/
	.main-nav-right .list-inline-item .mdi.mdi-account-circle{
		font-size: 20px;
	    vertical-align: middle;
	    padding-right: 3px;
	}

	.main-nav-right .list-inline-item .btn-link.az{
		border-right: 0px solid #384042 !important;
	    font-size: 13px;
	    font-weight: 600;
	}
	.btn-secondary:hover {
	    color: #fff;
	    box-shadow: 2px 0px 11px 4px #ffffff47;
	}

</style>