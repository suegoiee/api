<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Uanalyze Admin</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('thirdparty/open-iconic/font/css/open-iconic-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/layout.css')}}">
    <link rel="stylesheet" href="{{asset('css/sidebar.css')}}">
	@yield('css_file')  
    <!-- Scripts -->
   
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
        var WEB_PATH = "{{url('/')}}";
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
	<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
		<a class="navbar-brand" href="{{ url('/') }}">Uanalyze Admin</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="{{ url('/') }}">API Explorer <span class="sr-only">(current)</span></a>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
                @if (session('admin'))
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                           <span class="oi oi-person"></span> {{ session('admin_name') }}<span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                        	<a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                <span class="glyphicon glyphicon-off"></span> {{trans('auth.logout')}}
                            </a>
                            <form id="logout-form" action="{{ url('/admin/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <div class="arrow-border"></div>
                            <div class="arrow"></div>
                        </div>
                    </li>
                @else
                	<li class="nav-item active" >
                		<a  class="nav-link" href="{{ url('/admin/login') }}">{{trans('auth.login')}}</a>
                	</li>
                @endif
            </ul>
		</div>
	</nav>
	<div class="wrapper">
		@if(session('admin'))
			@include('layouts.sidebar')
		@endif
		@yield('content')
	</div>
	<footer class="footer">
		<div class="container">
			<span class="text-muted">Version 0.1.0 . Copyright © 2017. All rights reserved.</span>
		</div>
    </footer>
    <!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
	<script>
		$(function(){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
            $('#sidebarToggle').on('click', function () {
                $('.sidebar').toggleClass('active');
            });
		});
	</script>
    @yield('javascript')  
</body>
</html>
