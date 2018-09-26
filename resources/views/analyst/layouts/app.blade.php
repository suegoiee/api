<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Uanalyze Analyst</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap4/css/bootstrap.min.css')}}" >
    <link rel="stylesheet" href="{{asset('thirdparty/open-iconic/font/css/open-iconic-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/layout.css')}}">
    <link rel="stylesheet" href="{{asset('css/sidebar.css')}}">
    <link rel="stylesheet" href="{{asset('css/analyst.css')}}">
	@yield('css_file')  
    <!-- Scripts -->
   
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
		function url(uri){
            if(!uri){
                uri='/';
            }else if(uri[0]!='/'){
                uri='/'+uri;
            }
            return '{{ url("/") }}'+uri;
        } 
    </script>
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
		<a class="navbar-brand" href="{{ url('/analyst') }}">Uanalyze Analyst</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto">
				
			</ul>
			<ul class="nav navbar-nav navbar-right">
                @if (Auth::guard('analyst')->check())
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                           <span class="oi oi-person"></span> {{Auth::guard('analyst')->user()->name }}<span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                        	<a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                <span class="glyphicon glyphicon-off"></span> {{trans('auth.logout')}}
                            </a>
                            <form id="logout-form" action="{{ url('/analyst/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <div class="arrow-border"></div>
                            <div class="arrow"></div>
                        </div>
                    </li>
                @else
                	<li class="nav-item active" >
                		<a  class="nav-link" href="{{ url('/analyst/login') }}">{{trans('auth.login')}}</a>
                	</li>
                @endif
            </ul>
		</div>
	</nav>
	<div class="wrapper">
		@if(Auth::guard('analyst')->check())
			@include('analyst.layouts.sidebar')
		@endif
        <div id="content" class="w-100">
            <div class="px-4 pt-4 h-100">
                @yield('content')
            </div>
            <footer class="footer">
                <div class="container-fluid ">
                    <span class="text-muted author">Version 0.1.0 . Copyright © 2017. All rights reserved.</span>
                </div>
            </footer>
        </div>
	</div>
    <!-- Scripts -->
	<script src="{{asset('thirdparty/jquery/jquery-3.2.1.min.js')}}" ></script>
	<script src="{{asset('thirdparty/popper/popper.min.js')}}" ></script>
	<script src="{{asset('thirdparty/bootstrap4/js/bootstrap.min.js')}}" ></script>
	<script>
		$(function(){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
            $('#sidebarToggle').on('click', function () {
                $('.sidebar').toggleClass('active');
                var aria_expanded = $(this).attr("aria-expanded");
                aria_expanded = aria_expanded=='true'?'false':'true';
                $(this).attr("aria-expanded",aria_expanded);
            });
		});
	</script>
    @yield('javascript')  
</body>
</html>
