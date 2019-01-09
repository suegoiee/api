<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>優分析達人後台</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('../thirdparty/bootstrap4/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('../css/layout.css')}}">
    <link rel="stylesheet" href="{{asset('../css/sidebar.css')}}">
    <link rel="stylesheet" href="{{asset('../css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

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
	<nav class="navbar navbar-expand-md navbar-light fixed-top">
		<a class="navbar-brand" href="{{ url('/analyst') }}">
			<img src="{{asset('images/LOGO.svg')}}">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<i class="fas fa-bars" id="bars"></i>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto">
				
			</ul>
			<ul class="nav navbar-nav navbar-right">
                @if (Auth::guard('analyst')->check())
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                           <i class="fa fa-user"></i>  
                           	{{Auth::guard('analyst')->user()->name }}
                           	<span class="caret"></span>
                        </a>
                        <div class="dropdown-menu bg-dark dropdown-menu-right" role="menu">
                        	<a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                <span class="glyphicon glyphicon-off"></span> 
                                	{{trans('auth.logout')}}   
                                <i class="fa fa-sign-out-alt"></i>
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
        <div id="content">
            <div class="px-4 py-4">
                @yield('content')
            </div>
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
