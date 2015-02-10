@extends('front.layouts.master')

@section('head_title')
CAMSCON
@stop

@section('head_styles')
@stop

@section('content')
<div class="profile-wrapper">
	@if(!empty($profile->profileCover))
	<figure class="profile-cover">
		<img src="{{$profile->profileCover->url}}" />
	</figure>
	@endif

	<div class="profile-data" style="background-image:url(@if($profile->profileImage){{$profile->profileImage->url}}@else{{asset('front-assets/profile/profile_default_big.png')}}@endif);">
		<h3 class="name">{{{$profile->nickname}}} @if(Auth::check() && $profile->id===Auth::user()->id)<a href="{{action('ProfileController@showEditor')}}" class="btn btn-primary btn-xs">Edit profile</a>@endif</h3>
		<div class="row">
			<div class="details col-xs-12 col-sm-8">
				@if(!empty($profile->slug))
				<p>MY PAGE <a href="{{action('ProfileController@showProfile', $profile->slug)}}">{{action('ProfileController@showProfile', $profile->slug)}}</a></p>
				@else
				<p>MY PAGE <a href="{{action('ProfileController@showProfile', $profile->id)}}">{{action('ProfileController@showProfile', $profile->id)}}</a></p>
				@endif
				<p>Blog @if(!empty($profile->blog))<a href="{{$profile->blog}}" target="_blank">{{$profile->blog}}</a>@else{{'-'}}@endif</p>
				<p>Instagram @if(!empty($profile->instagram))<a href="http://instagram.com/{{$profile->instagram}}" target="_blank">{{'@'.$profile->instagram}}</a>@else{{'-'}}@endif</p>
				<p style="width: 200px; border: 1px solid black; background-color:#DDD; text-align:center">@if(!empty($profile->intro)){{nl2br($profile->intro)}}@endif</p>
			</div>
			<div class="stats col-xs-12 col-sm-4">
				<h3><strong class="stat-value">{{$stats->posts}}</strong> posts</h3>
				<h3><strong class="stat-value">{{$stats->likes}}</strong> likes</h3>
				<h3><strong class="stat-value">{{$stats->comments}}</strong> comments</h3>
			</div>
		</div>
	</div>

	<div class="profile-filter-tabs">
		<div class="my-collection tab active" data-filter="liked">My Collection</div><div class="my-posts tab" data-filter="mine">My Posts</div>
	</div>
	<div id="likedListWrapper" class="snap-list"></div>
	<div id="myListWrapper" class="snap-list"></div>
</div><!--/.profile-wrapper-->
@stop

@section('footer_scripts')
<script type="text/javascript" src="{{asset('packages/isotope/isotope.pkgd.min.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>

<script type="text/javascript">
var ProfileView={
	objx:{
		myList:null,
		likedList:null,
		tabs:null
	},
	display:{
		screenWidth:0,
		wrapperWidth:0,
		columnWidth:0
	},
	isotope:{
		status:'inactive'
	},
	snaps:{
		mine:{{$mySnaps}},
		liked:{{$likedSnaps}}
	},
	endpoints:{
		loadMine:"{{$loadMine}}",
		loadLiked:"{{$loadLiked}}"
	},
	status:'idle',
	filter:'liked',
	init:function() {
		this.objx.myList=$('#myListWrapper');
		this.objx.likedList=$('#likedListWrapper');
		this.objx.tabs=$('.profile-filter-tabs');

		this.initIsotope();

		//Proc initial data
		this.appendSnaps('mine', this.snaps.mine.data);
		this.appendSnaps('liked', this.snaps.liked.data);
		this.objx.myList.css('display','none');

		//Scroll event
		$(window).on('scroll', null, null, function() {
			if((document.body.scrollHeight-$(window).scrollTop() < $.viewportH()+500) && (ProfileView.status=='idle')) {
				ProfileView.status='loading';
				ProfileView.requestMoreSnaps();
			}
		});

		//Filter tabs
		this.objx.tabs.on('click', '.tab', null, function() {
			if(!$(this).hasClass('active')) {
				var filter=$(this).attr('data-filter');
				if(filter=='liked') {
					$(this).addClass('active');
					$(this).siblings('.my-posts').removeClass('active');
					ProfileView.objx.likedList.css('display', 'block');
					ProfileView.objx.myList.css('display', 'none');
					ProfileView.filter='liked';
				} else {
					$(this).addClass('active');
					$(this).siblings('.my-collection').removeClass('active');
					ProfileView.objx.myList.css('display', 'block');
					ProfileView.objx.likedList.css('display', 'none');
					ProfileView.filter='mine';
				}
				//Relayout
				ProfileView.refreshLayout();
			}
		});
	},
	initIsotope:function() {
		//Set display dimensions
		this.display.screenWidth=$.viewportW();
		if(this.filter=='liked') {
			this.display.wrapperWidth=this.objx.likedList.innerWidth();
		} else {
			this.display.wrapperWidth=this.objx.myList.innerWidth();
		}
		if(this.display.screenWidth<768) {//Mobile
			this.display.columnWidth=this.display.wrapperWidth;
		} else if(this.display.screenWidth<992) {//Tablets
			this.display.columnWidth=this.display.wrapperWidth/3;
		} else if(this.display.screenWidth<1200) {//Laptops
			this.display.columnWidth=this.display.wrapperWidth/3;
		} else {//Desktops
			this.display.columnWidth=this.display.wrapperWidth/3;
		}

		//Init isotope
		if(this.isotope.status=='active') {
			this.objx.myList.empty().isotope('destroy');
			this.objx.likedList.empty().isotope('destroy');
		}
		this.objx.myList.isotope({
			itemSelector:'.snap-wrapper',
			layoutMode:'masonry',
			masonry:{
				columnWidth:this.display.columnWidth
			}
		});
		this.objx.likedList.isotope({
			itemSelector:'.snap-wrapper',
			layoutMode:'masonry',
			masonry:{
				columnWidth:this.display.columnWidth
			}
		});
		this.isotope.status='active';
	},
	appendSnaps:function(list,snaps) {
		//Create array of snap nodes from data
		var snapObjx=[];
		var slen=snaps.length;for(var i=0;i<slen;i++) {
			var wrapper=$('<div class="snap-wrapper hidden"></div>');
			var inner=$('<div class="snap-inner"></div>');

			var snap=$('<figure class="snap"></figure>');
			var likeBtnClass=null;
			if(snaps[i].liked.length>0) {
				likeBtnClass='liked';
			}
			var likeBtn=$('<button type="button" data-type="" data-id="" class="like-btn"></button>').attr('data-type', 'StreetSnap').attr('data-id', snaps[i].id).addClass(likeBtnClass);
			$('<span class="total-likes" data-target-type="StreetSnap" data-target-id=""></span>').attr('data-target-id', snaps[i].id).text(snaps[i].cached_total_likes).appendTo(likeBtn);
			$('<span class="caption"></span>').appendTo(likeBtn);
			likeBtn.appendTo(snap);
			$('<button type="button" class="fb-share-btn" data-url=""></button>').attr('data-url', snaps[i].single_url).appendTo(snap);
			var link=$('<a href=""></a>').attr('href', snaps[i].single_url);
			$('<img src="" alt="" class="snap-primary" />').attr('src', snaps[i].primary.url).attr('width', snaps[i].primary.width).attr('height', snaps[i].primary.height).appendTo(link);
			link.appendTo(snap);
			snap.appendTo(inner);

			var meta=$('<div class="meta-container clearfix"></div>');
			
			if(snaps[i].user.profile_image) {
				$('<img src="" alt="" class="author-profile" />').attr('src', snaps[i].user.profile_image.url).appendTo(meta);
			} else {
				$('<img src="" alt="" class="author-profile" />').attr('src', "{{asset('front-assets/profile/profile_default_small.png')}}").appendTo(meta);
			}

			var subjectMeta=$('<div class="subject-meta"></div>');
			if(snaps[i].meta_type=='BlogMeta') {
				$('<strong></strong>').text(snaps[i].meta.name).appendTo(subjectMeta);
				$('<strong class="meta-category"></strong>').text(snaps[i].meta.country).appendTo(subjectMeta);
			} else {
				$('<strong></strong>').text(snaps[i].name).appendTo(subjectMeta);
				$('<strong class="meta-category"></strong>').text(snaps[i].meta.name).appendTo(subjectMeta);
			}
			subjectMeta.appendTo(meta);

			var authorMeta=$('<div class="author-meta">Photo by </div>').append(snaps[i].user.nickname).appendTo(meta);

			meta.appendTo(inner);

			inner.appendTo(wrapper);

			snapObjx.push(wrapper.get(0));
		}

		var wrapper=null;
		if(list=='mine') {
			wrapper=this.objx.myList;
		} else {
			wrapper=this.objx.likedList;
		}
		wrapper.append(snapObjx).imagesLoaded(function() {
			wrapper.find('.snap-wrapper').each(function() {
				$(this).removeClass('hidden');
			});
			wrapper.isotope('appended', snapObjx);
		});
	},
	refreshLayout:function() {
		if(this.filter=='liked') {
			this.objx.likedList.isotope('layout');
		} else {
			this.objx.myList.isotope('layout');
		}
	},
	requestMoreSnaps:function() {
		var endpoint=null;
		if(this.filter=='mine') {
			endpoint=this.endpoints.loadMine;
		} else if(this.filter=='liked') {
			endpoint=this.endpoints.loadLiked;
		}
		if( (typeof endpoint != 'undefined') && (endpoint != '') ) {
			$.get(endpoint, null, function(response) {
				if(typeof response==='object' && 'snaps' in response && 'more_url' in response) {
					if(ProfileView.filter=='mine') {
						ProfileView.endpoints.loadMine=response.more_url;
						ProfileView.snaps.mine.data.concat(response.snaps.data);
						ProfileView.appendSnaps('mine', response.snaps.data);
					} else if(ProfileView.filter=='liked') {
						ProfileView.endpoints.loadLiked=response.more_url;
						ProfileView.snaps.liked.data.concat(response.snaps.data);
						ProfileView.appendSnaps('liked', response.snaps.data);
					}
					ProfileView.status='idle';
				}
			}, 'json');
		} else {
			this.status='idle';
		}
	}
};//ProfileView{}

$(document).ready(function() {
	ProfileView.init();
});

$(window).resize(function() {
	var newScreenWidth=$.viewportW();
	if(newScreenWidth!=ProfileView.display.screenWidth) {
		ProfileView.init();
	}
});

var LikeButtons={
	init:function() {
		$(document).on('click', '.like-btn', null, function(e) {
			LikeButtons.like($(this));
		});
	},
	like:function(btn) {
		btn.prop('disabled', true);

		var data={
			_token:"{{csrf_token()}}",
			target_type:btn.attr('data-type'),
			target_id:btn.attr('data-id')
		}

		$.post("{{action('LikeController@procLike')}}", data, function(response) {
			//console.log(response);
			if(response.proc=='liked') {
				btn.addClass('liked');
			} else if(response.proc=='canceled') {
				btn.removeClass('liked');
			}
			if('total' in response) {
				btn.siblings('span.likes').text(response.total);
			}
		}, 'json').fail(function(response) {
			//console.log(response.status);
			if(response.status==401) {
				if(typeof LoginModal === 'object') {
					LoginModal.launch();
				}
			}
		}).always(function() {
			btn.prop('disabled', false);
		});
	}
};//LikeButtons{}

$(document).ready(function() {
	LikeButtons.init();
});
</script>
@stop