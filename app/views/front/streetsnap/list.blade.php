@extends('front.layouts.master')

@section('head_title')
Camscon
@stop

@section('head_styles')
@stop

@section('content')
<div id="snapListWrapper" class="snap-list"></div>
@stop

@section('footer_scripts')
<script type="text/javascript" src="{{asset('packages/isotope/isotope.pkgd.min.js')}}"></script>
<script type="text/javascript" src="{{asset('packages/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>

<script type="text/javascript">
var ListView={
	objx:{
		wrapper:null
	},
	display:{
		screenWidth:0,
		wrapperWidth:0,
		columnWidth:0
	},
	isotope:{
		status:'inactive'
	},
	snaps:{{$snaps}},
	endpoints:{
		loadMore:"{{$loadMore}}"
	},
	init:function() {
		//Set wrapper object
		this.objx.wrapper=$('#snapListWrapper');

		//Set display dimensions
		this.display.screenWidth=$.viewportW();
		this.display.wrapperWidth=this.objx.wrapper.innerWidth();
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
			this.objx.wrapper.empty().isotope('destroy');
		}
		this.objx.wrapper.isotope({
			itemSelector:'.snap-wrapper',
			layoutMode:'masonry',
			masonry:{
				columnWidth:this.display.columnWidth
			}
		});
		this.isotope.status='active';

		//Proc initial data
		this.appendSnaps(this.snaps.data);
	},
	appendSnaps:function(snaps) {
		//Create array of snap nodes from data
		var snapObjx=[];
		var slen=snaps.length;for(var i=0;i<slen;i++) {
			var wrapper=$('<div class="snap-wrapper hidden"></div>');
			var inner=$('<div class="snap-inner"></div>');

			var snap=$('<figure class="snap"></figure>');
			$('<button type="button" class="like-btn">LIKE</button>').appendTo(snap);
			$('<span class="likes"></span>').text(snaps[i].cached_total_likes).appendTo(snap);
			$('<button type="button" class="fb-share-btn">f</button>').appendTo(snap);
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
			$('<strong></strong>').text(snaps[i].name).appendTo(subjectMeta);
			$('<strong class="meta-category"></strong>').text(snaps[i].meta.name).appendTo(subjectMeta);
			subjectMeta.appendTo(meta);

			var authorMeta=$('<div class="author-meta">Photo by </div>').append(snaps[i].user.nickname).appendTo(meta);

			meta.appendTo(inner);

			inner.appendTo(wrapper);

			snapObjx.push(wrapper.get(0));
		}
		
		//Append to isotope instance
		//this.objx.wrapper.append(snapObjx).isotope('appended', snapObjx).isotope('layout');

		var wrapper=this.objx.wrapper;
		wrapper.append(snapObjx).imagesLoaded(function() {
			wrapper.find('.snap-wrapper').each(function() {
				$(this).removeClass('hidden');
			});
			wrapper.isotope('appended', snapObjx);
		});
	},
	refreshLayout:function() {
		console.log(this.display);
		this.objx.wrapper.isotope('layout');
	},
	requestMoreSnaps:function() {
		if(this.endpoints.loadMore!='') {
			$.get(this.endpoints.loadMore, null, function(response) {
				if(typeof response==='object' && 'snaps' in response && 'more_url' in response) {
					ListView.endpoints.loadMore=response.snaps.more_url;
					ListView.snaps.data.concat(response.snaps.data);
					ListView.appendSnaps(response.snaps.data);
				}
			}, 'json');
		}
	}
};//ListView{}

$(document).ready(function() {
	ListView.init();
});

$(window).resize(function() {
	ListView.init();
});
</script>
@stop