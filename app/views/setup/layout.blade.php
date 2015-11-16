@extends("layout")
@section("content")
    
    <!--=== Menu ===-->
    <div class="parallax-team parallaxBg">
        <div class="row">
            <!-- side menu -->
            @include("setup.menu")
            
            <!-- each page under setup module -->
            @yield("setup_content")
        </div>
    </div>  
    <!--=== End Menu ===-->
    
@stop