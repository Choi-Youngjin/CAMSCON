<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimal-ui">
	<title>@yield('head_title','CAMSCON')</title>

	@if(Route::current()->uri()=='/')
	<!--FB Open Graph tags for main page-->
	<meta property="og:title" content="캠스콘" />
	<meta property="og:site_name" content="CAMSCON" />
	<meta property="og:url" content="http://camscon.kr" />
	<meta property="og:image" content="http://cdn.camscon.kr/front-assets/layouts/fb_og.jpg" />
	<meta property="og:description" content="Share your inspiration!" />
	<meta property="fb:app_id" content="562009567255774" />
	<meta property="og:locale" content="ko_KR" />
	@endif

	<!--jQuery UI-->
	<!-- <link href="{{asset('packages/jquery-ui-1.11.0-hot-sneaks-full/jquery-ui.min.css')}}" rel="stylesheet" /> -->

	<!-- Bootstrap -->
	<link href="{{asset('packages/bootstrap-3.2.0/css/bootstrap.min.css')}}" rel="stylesheet" />

	<!--Fontello-->
	<link href="{{asset('packages/fontello-71f5629c/css/fontello.css')}}" rel="stylesheet" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!--Google Web Fonts-->
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css' />

	<!--Layout styles-->
	<link href="{{asset('front-assets/layouts/master.d8a3a870d2.css')}}" rel="stylesheet" />

	@yield('head_styles')
	</head>
<body>
@include('includes.facebook-sdk')
@include('includes.google-analytics')
	<header class="layout-header">
		<div class="top-row container">
			<div class="site-logo">
				<a href="{{url('/')}}"><img src="http://cdn.camscon.kr/front-assets/layouts/logo.png" alt="Camscon" /></a>
			</div>

			<nav class="site-nav clearfix">
				<ul>
					@if(isset($RouteParams) && $RouteParams->category=='all' && $RouteParams->slug=='order' && $RouteParams->ordering=='hot')
					<li><a href="{{action('StreetSnapController@getList', array('category'=>'all', 'slug'=>'order', 'ordering'=>'hot'))}}" class="active">Hot</a></li>
					@else
					<li><a href="{{action('StreetSnapController@getList', array('category'=>'all', 'slug'=>'order', 'ordering'=>'hot'))}}">Hot</a></li>
					@endif
					@if(isset($RouteParams) && $RouteParams->category=='all' && $RouteParams->slug=='order' && $RouteParams->ordering=='new')
					<li><a href="{{action('StreetSnapController@getList', array('category'=>'all', 'slug'=>'order', 'ordering'=>'new'))}}" class="active">New</a></li>
					@else
					<li><a href="{{action('StreetSnapController@getList', array('category'=>'all', 'slug'=>'order', 'ordering'=>'new'))}}">New</a></li>
					@endif
					<li><a href="#" class="deactivated">Editorial</a></li>
					<li><a href="#" class="deactivated">Inspirer</a></li>
				</ul>
			</nav>

			<div class="mobile-nav-btn">
				<span class="bar"></span>
				<span class="bar"></span>
				<span class="bar"></span>				
			</div>

			<div id="UserBox" class="site-user">
				@if(Auth::check())
				@include('includes.user-box')
				@else
				<a href="#" id="camsconLoginBtn" class="login-btn">Login / Sign up</a>
				@endif
			</div>

			<div class="site-search">
				<input type="text" name="search_string" placeholder="보고싶은 학교, 거리, 브랜드 등을 입력하세요" />
			</div>
		</div>
		<div class="bottom-row container">
			<nav class="category-nav">
				<a href="#" class="mobile-cat-btn">Fashion Inspiration <span class="caret"></span></a>
				<div class="category-list-wrapper">
					<ul class="category-list clearfix">
						<li class="campus-menu">
							<a href="{{action('StreetSnapController@getList', array('category'=>'campus', 'slug'=>'all', 'ordering'=>'new'))}}" class="parent">Campus <span class="caret"></span></a>
							<span class="close-btn glyphicon glyphicon-remove"></span>
							<ul class="campus-sub-menu sub-menu row">
								<li class="col-xs-12 col-sm-3 col-md-2 mobile-all"><a href="{{action('StreetSnapController@getList', array('category'=>'campus', 'slug'=>'all', 'ordering'=>'new'))}}">All</a></li>
								@include('includes.campus-name')
							</ul>
						</li>
						<li class="street-menu">
							<a href="{{action('StreetSnapController@getList', array('category'=>'street', 'slug'=>'all', 'ordering'=>'new'))}}" class="parent">Street <span class="caret"></span></a>
							<span class="close-btn glyphicon glyphicon-remove"></span>
							<ul class="street-sub-menu sub-menu">
								<li class="mobile-all"><a href="{{action('StreetSnapController@getList', array('category'=>'street', 'slug'=>'all', 'ordering'=>'new'))}}">All</a></li>
								@foreach($CatNav->street as $street)
								<li class="">
									<a href="{{action('StreetSnapController@getList', array('category'=>'street', 'slug'=>$street->slug, 'ordering'=>'new'))}}">{{$street->name}}</a>
								</li>
								@endforeach
							</ul>
						</li>
						<li class="blog-menu">
							<a href="{{action('StreetSnapController@getList', array('category'=>'blog', 'slug'=>'all', 'ordering'=>'new'))}}">Fashion People <span class="caret"></span></a>
							<span class="close-btn glyphicon glyphicon-remove"></span>
							<ul class="blog-sub-menu sub-menu">
								<li class="col-xs-hide col-sm-hide col-md-12 inspirer-register-menu"><a href="{{action('InspirerRegisterController@showRegister')}}">Register as a fashion Inspirer!</a></li>
								<li class="col-xs-12 col-sm-3 col-md-2 mobile-all"><a href="{{action('StreetSnapController@getList', array('category'=>'blog', 'slug'=>'all', 'ordering'=>'new'))}}">All</a></li>
								@foreach($CatNav->blog as $blog)
								<li class="col-xs-12 col-sm-3 col-md-2">
									<a href="{{action('StreetSnapController@getList', array('category'=>'blog', 'slug'=>$blog->slug, 'ordering'=>'new'))}}">{{$blog->name}}</a>
								</li>
								@endforeach
							</ul>
						</li>
						<li><a href="" class="deactivated">Brand <span class="caret"></span></a></li>
						<li class="fashionweek-menu">
							<a href="{{action('StreetSnapController@getList', array('category'=>'fashion-week', 'slug'=>'all', 'ordering'=>'new'))}}" class="parent">Fashion week <span class="caret"></span></a>
							<span class="close-btn glyphicon glyphicon-remove"></span>
							<ul class="fashionweek-sub-menu sub-menu">
								<li class="mobile-all"><a href="{{action('StreetSnapController@getList', array('category'=>'fashion-week', 'slug'=>'all', 'ordering'=>'new'))}}">All</a></li>
								@foreach($CatNav->fashionweek as $fashionweek)
								<li class="">
									<a href="{{action('StreetSnapController@getList', array('category'=>'fashion-week', 'slug'=>$fashionweek->slug, 'ordering'=>'new'))}}">{{$fashionweek->name}}</a>
								</li>
								@endforeach
							</ul>
						</li>
						<li class="festival-menu sorted">
							<a href="{{action('StreetSnapController@getList', array('category'=>'festival', 'slug'=>'all', 'ordering'=>'new'))}}" class="parent">Festival/Club <span class="caret"></span></a>
							<span class="close-btn glyphicon glyphicon-remove"></span>
							<ul class="festival-sub-menu sub-menu">
								<li class="mobile-all"><a href="{{action('StreetSnapController@getList', array('category'=>'festival', 'slug'=>'all', 'ordering'=>'new'))}}">All</a></li>
								@foreach($CatNav->festival as $festival)
								<li class="">
									<a href="{{action('StreetSnapController@getList', array('category'=>'festival', 'slug'=>$festival->slug, 'ordering'=>'new'))}}">{{$festival->name}}</a>
								</li>
								@endforeach
							</ul>
						</li>
						<li class="gender-menu sorted">
							<a href="#" class="parent">Men/Ladies <span class="caret"></span></a>
							<span class="close-btn glyphicon glyphicon-remove"></span>
							<ul class="gender-sub-menu sub-menu row">
								<li><a href="{{action('StreetSnapController@getList', array('category'=>'filter', 'slug'=>'men'))}}">Men</a></li>
								<li><a href="{{action('StreetSnapController@getList', array('category'=>'filter', 'slug'=>'ladies'))}}">Ladies</a></li>
							</ul>
						</li>
						<li><a href="{{action('StreetSnapEditController@showStarter')}}" class="post-btn"><span class="glyphicon glyphicon-camera"></span> Post</a></li>
					</ul>
					<div class="misc-links-mobile">
						<span><a href="{{url('legal/privacy-policy')}}">개인정보취급방침</a></span>
						<span><a href="{{url('legal/terms-of-use')}}">이용약관</a></span>
						<span><a href="{{action('UserFeedbackController@showForm')}}">Feedback</a></span>
					</div>
				</div>
			</nav>
			@if(isset($RouteParams) && $RouteParams->category=='all' && $RouteParams->slug=='order' && $RouteParams->ordering=='new')
			<div class="mobile-new-btn"><a href="{{action('StreetSnapController@getList', array('category'=>'all', 'slug'=>'order', 'ordering'=>'new'))}}" class="active">New</a></div>
			@else
			<div class="mobile-new-btn"><a href="{{action('StreetSnapController@getList', array('category'=>'all', 'slug'=>'order', 'ordering'=>'new'))}}">New</a></div>
			@endif
			@if(isset($RouteParams) && $RouteParams->category=='all' && $RouteParams->slug=='order' && $RouteParams->ordering=='hot')
			<div class="mobile-hot-btn"><a href="{{action('StreetSnapController@getList', array('category'=>'all', 'slug'=>'order', 'ordering'=>'hot'))}}" class="active">Hot</a></div>
			@else
			<div class="mobile-hot-btn"><a href="{{action('StreetSnapController@getList', array('category'=>'all', 'slug'=>'order', 'ordering'=>'hot'))}}">Hot</a></div>
			@endif
		</div>
	</header><!--/.layout-header-->

	<main class="layout-body container">
		@yield('content')
	</main><!--/.layout-body-->

	<footer id="layoutFooter" class="layout-footer">
		<div class="top-btn-wrapper container">
			<div id="scrollTopBtn" class="scroll-top-btn">TOP▲</div>
		</div>
		<div class="footer-content container">
			<span><a href="{{action('UserFeedbackController@showForm')}}">Feedback</a></span>
			<span><a href="{{url('legal/privacy-policy')}}">개인정보취급방침</a></span>
			<span><a href="{{url('legal/terms-of-use')}}">이용약관</a></span>
		</div>
	</footer><!--/.layout-footer-->

	<!-- jQuery 1.11.1 -->
	<script src="{{asset('packages/jquery-1.11.1/jquery-1.11.1.min.js')}}"></script>
	
	<!-- Bootstrap 3.2.0 -->
	<script src="{{asset('packages/bootstrap-3.2.0/js/bootstrap.min.js')}}"></script>

	<!--Login modal-->
	@if(!Auth::check())
	@include('includes.login-modal', array('tracker'=>Tracker::get()))
	@endif

	<!--Alert/Confirm modals-->
	@include('includes.confirm-modal')
	@include('includes.alert-modal')

	<!--Verge.js-->
	<script src="{{asset('packages/verge/verge.min.js')}}"></script>
	<script type="text/javascript">
		jQuery.extend(verge);
	</script>

	<!--Admin Layout-->
	<script src="{{asset('front-assets/layouts/master.76bfe9b0b9.js')}}?version=1.0.0"></script>
	@yield('footer_scripts')
</body>
</html>