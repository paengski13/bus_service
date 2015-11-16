@section("header")
    <!--=== Header ===-->    
    <div class="header">
        <!-- Topbar -->
        <div class="topbar">
            <div class="container">
                <!-- Topbar Navigation -->
                <ul class="loginbar pull-right">
                    @if (Auth::check())
                        <li><a href="javascript:void(0);"><span aria-hidden="true" class="icon-user"></span> {{ Auth::user()->user_fullname }}</a></li>
                        <li class="topbar-devider"></li>
                        <li><a href="{{ URL::to('logout') }}"><span aria-hidden="true" class="icon-logout"></span> Logout</a></li>
                    @else
                        <li><a></a></li>   
                    @endif
                </ul>
                <!-- End Topbar Navigation -->
            </div>
        </div>
        <!-- End Topbar -->
    
        <!-- Navbar -->
        <div class="navbar navbar-default mega-menu" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <!--a class="navbar-brand" href="{{ URL::to('performance/home') }}">
                        <img id="logo-header" src="{{ URL::to('assets/img/spr_logo.png') }}" alt="Logo" >
                    </a-->
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-responsive-collapse collapseie8">
                    @include("menu")
                </div><!--/navbar-collapse-->
            </div>    
        </div>            
        <!-- End Navbar -->
    </div>
    <!--=== End Header ===-->
	
    <!--=== Content Part ===-->
	
    <div class="container"> 
        <!-- Page Title -->
        <div id="clients-flexslider" class="flexslider home clients">
			<!--a name="hash"></a>
            <div class="pull-left headline"><h2>{{ isset($data['page_title']) ? $data['page_title'] : '-- No Title --' }}</h2></div-->
        </div>
        <!-- End Page Title -->
		
    
        <!-- system message -->
        @if (Session::has('success'))
            <div class="alert alert-success fade in">{{ Session::get('success') }}</div>
        @elseif (Session::has('danger'))
            <div class="alert alert-danger fade in">{{ Session::get('danger') }}</div>
        @elseif (Session::has('warning'))
            <div class="alert alert-warning fade in">{{ Session::get('warning') }}</div>
        @elseif (Session::has('info'))
            <div class="alert alert-info fade in">{{ Session::get('info') }}</div>
        @endif
    
@show