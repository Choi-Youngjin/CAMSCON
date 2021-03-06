<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('head_title','Camscon')</title>

	<!--jQuery UI-->
	<link href="{{asset('packages/jquery-ui-1.11.0-hot-sneaks-full/jquery-ui.min.css')}}" rel="stylesheet" />

	<!-- Bootstrap -->
	<link href="{{asset('packages/bootstrap-3.2.0/css/bootstrap.min.css')}}" rel="stylesheet" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!--Admin Layout styles-->
	<link href="{{asset('mockup-assets/layout/style.css')}}" rel="stylesheet" />

	@yield('head_styles')
	</head>
<body>
@include('includes.facebook-sdk')
	<header class="layout-header">
		<div class="top-row container">
			<div class="site-logo">
				<img src="{{asset('mockup-assets/layout/logo.png')}}" alt="Camscon" />
			</div>

			<nav class="site-nav clearfix">
				<ul>
					<li><a href="">Hot</a></li>
					<li><a href="">New</a></li>
					<li><a href="">Editorial</a></li>
					<li><a href="">Inspirer</a></li>
				</ul>
			</nav>

			<div class="site-user">
				<ul class="clearfix">
					<li><a href="">Login</a></li>
					<li><a href="">Sign up</a></li>
				</ul>
			</div>

			<div class="site-search">
				<input type="text" name="search_string" placeholder="보고싶은 학교, 거리, 브랜드 등을 입력하세요" />
			</div>
		</div>
		<div class="bottom-row container">
			<nav class="category-nav">
				<ul class="clearfix">
					<li><a href="">All</a></li>
					<li><a href="">Campus <span class="caret"></span></a></li>
					<li><a href="">Street <span class="caret"></span></a></li>
					<li><a href="">Brand <span class="caret"></span></a></li>
					<li><a href="">Fashion week <span class="caret"></span></a></li>
					<li><a href="">Festival/Club <span class="caret"></span></a></li>
					<li><a href="" style="border-right: 1px solid #7f7f7f;">Dudes/Ladies <span class="caret"></span></a></li>
					<li><a href="" class="post-btn"><span class="glyphicon glyphicon-camera"></span> Post</a></li>
				</ul>
			</nav>
		</div>
	</header><!--/.layout-header-->

	<main class="layout-body container">
		@yield('content')
	</main><!--/.layout-body-->

	<footer class="layout-footer container">
		<div class="layout-footer-content">
		</div>
	</footer><!--/.layout-footer-->

	<!-- jQuery 1.11.1 -->
	<script src="{{asset('packages/jquery-1.11.1/jquery-1.11.1.min.js')}}"></script>
	<!--jQuery UI-->
	<script src="{{asset('packages/jquery-ui-1.11.0-hot-sneaks-full/jquery-ui.min.js')}}"></script>
	<!-- Bootstrap 3.2.0 -->
	<script src="{{asset('packages/bootstrap-3.2.0/js/bootstrap.min.js')}}"></script>
	<!--Verge.js-->
	<script src="{{asset('packages/verge/verge.min.js')}}"></script>
	<script type="text/javascript">
		jQuery.extend(verge);
	</script>

	<!--Admin Layout-->
	<script src="{{asset('mockup-assets/layout/master.js')}}"></script>
	@yield('footer_scripts')
</body>
</html>