@extends('front.layouts.master')

@section('head_title')
CAMSCON
@stop

@section('head_styles')
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/profile/editor.css')}}" />
@stop

@section('content')
<div class="profile-editor">
	@if(empty($profile->profileCover))
	<figure id="profileCover" class="profile-cover empty">
		<button type="button" id="uploadCoverBtn" class="upload-cover-btn btn btn-primary">커버사진 업로드</button>
	</figure>
	@else
	<figure id="profileCover" class="profile-cover">
		<img src="{{$profile->profileCover->url}}" />
		<button type="button" id="uploadCoverBtn" class="upload-cover-btn btn btn-primary">커버사진 업로드</button>
	</figure>
	@endif

	<div id="profileData" class="profile-data" style="background-image:url(@if($profile->profileImage){{$profile->profileImage->url}}@else{{asset('front-assets/profile/profile_default_big.png')}}@endif);">
		<h3 class="name">{{{$profile->nickname}}}</h3>
		<div class="row">
			<div class="details col-xs-12 col-sm-6">
				@if(!empty($profile->slug))
				<p>MY PAGE <a href="{{action('ProfileController@showProfile', $profile->slug)}}">{{action('ProfileController@showProfile', $profile->slug)}}</a></p>
				@else
				<p>MY PAGE <a href="{{action('ProfileController@showProfile', $profile->id)}}">{{action('ProfileController@showProfile', $profile->id)}}</a></p>
				@endif
				<p>Blog @if(!empty($profile->blog))<a href="{{$profile->blog}}" target="_blank">{{$profile->blog}}</a>@else{{'-'}}@endif</p>
				<p>Instagram @if(!empty($profile->instagram))<a href="http://instagram.com/{{$profile->instagram}}" target="_blank">{{'@'.$profile->instagram}}</a>@else{{'-'}}@endif</p>
				<p><button type="button" id="uploadProfileBtn" class="btn btn-primary">프로필 사진 업로드</button></p>
				<br>
				<p style="border: 3px solid black; background-color:#DDD; padding: 10px; height:85px; text-align:center">@if(!empty($profile->intro)){{nl2br($profile->intro)}}@endif</p>
			</div>

			<div class="form-col col-xs-12 col-sm-6">
				@if(Session::has('profile_save'))
				<div class="alert alert-success"><strong>성공</strong> 프로필을 업데이트 했습니다! :)</div>
				@elseif(Session::has('profile_error'))
					@if(Session::get('profile_error')=='db_error')
					<div class="alert alert-danger"><strong>오류</strong> 데이터베이스 에러가 발생했습니다! :(</div>
					@elseif(Session::get('profile_error')=='validation')
					<div class="alert alert-danger"><strong>오류</strong> 입력하신 내용에 오류가 있습니다! :(</div>
					@endif
				@endif

				<div class="alert alert-info"><strong><span class="glyphicon glyphicon-ok"></span> Tip!</strong><br>1. 변경을 원하시는 항목만 입력하시기 바랍니다.<br>2. 페이스북으로 가입하신 분은 이메일과 비밀번호 변경이 불가능합니다 :(</div>

				{{ Form::open(array('url'=>action('ProfileController@saveProfile'))) }}
				<div class="form-group">
					<label for="profileNickname">Nickname</label>
					<input type="text" id="profileNickname" name="nickname" class="form-control" placeholder="닉네임" value="@if(Input::old('nickname')){{Input::old('nickname')}}@endif" />
					<?php
					if($errors->has('nickname')) {
						$msg='';
						switch($errors->first('nickname')) {
							case 'min':
								$msg='닉네임은 3글자 이상이어야 합니다!';
								break;
							case 'unique':
								$msg='이미 사용중인 닉네임 입니다!';
								break;
						}
						printf('<p class="text-danger">%s</p>', $msg);
					}
					?>
				</div>

				<div class="form-group">
					<label for="profileEmail">Email</label>
					@if($profile->enum!='facebook')
					<input type="email" id="profileEmail" name="email" class="form-control" placeholder="이메일" value="@if(Input::old('email')){{Input::old('email')}}@endif" />
					@else
					<input type="email" id="profileEmail" name="email" class="form-control" placeholder="이메일" disabled="disabled" value="@if(Input::old('email')){{Input::old('email')}}@endif" />
					@endif
					<?php
					if($errors->has('email')) {
						$msg='';
						switch($errors->first('email')) {
							case 'email':
								$msg='이메일 형식이 잘못되었습니다!';
								break;
							case 'unique':
								$msg='이미 사용중인 이메일 입니다!';
								break;
						}
						printf('<p class="text-danger">%s</p>', $msg);
					}
					?>
				</div>

				@if(Auth::user()->password)
				<div class="form-group">
					<label for="profilePswd">Password</label>
					<input type="password" id="profilePswd" name="password" class="form-control" placeholder="비밀번호" />
					<input type="password" name="password_confirmation" class="form-control" placeholder="비밀번호 확인" />
					<?php
					if($errors->has('password')) {
						$msg='';
						switch($errors->first('password')) {
							case 'min':
								$msg='비밀번호는 8자리 이상이어야 합니다!';
								break;
							case 'confirmed':
								$msg='비밀번호 확인이 일치하지 않습니다!';
								break;
						}
						printf('<p class="text-danger">%s</p>', $msg);
					}
					?>
				</div>
				@endif

				<div class="form-group">
					<label for="profileSlug">Profile slug</label>
					<p class="text-info">{{action('ProfileController@showProfile', '')}}/{이곳에 들어갈 부분}</p>
					<input type="text" id="profileSlug" name="slug" class="form-control" placeholder="영문, 대시, 하이픈으로만 구성할 수 있습니다" value="@if(Input::old('slug')){{Input::old('slug')}}@endif" />
					<?php
					if($errors->has('slug')) {
						$msg='';
						switch($errors->first('slug')) {
							case 'min':
								$msg='프로필 주소는 4글자 이상이어야 합니다!';
								break;
							case 'regex':
								$msg='프로필 주소는 영문, 대시, 하이픈으로만 구성할 수 있습니다!';
								break;
						}
						printf('<p class="text-danger">%s</p>', $msg);
					}
					?>
				</div>

				<div class="form-group">
					<label for="profileInstagram">Instagram</label>
					<input type="text" id="profileInstagram" name="instagram" class="form-control" placeholder="@ 제외 인스타그램 사용자명" value="@if(Input::old('instagram')){{Input::old('instagram')}}@endif" />
					<?php
					if($errors->has('instagram')) {
						$msg='';
						switch($errors->first('instagram')) {
							case 'max':
								$msg='인스타그램 사용자명은 30글자를 넘을 수 없습니다!';
								break;
							case 'regex':
								$msg='인스타그램 사용자명은 영문, 숫자, 하이픈으로만 구성할 수 있습니다!';
								break;
						}
						printf('<p class="text-danger">%s</p>', $msg);
					}
					?>
				</div>

				<div class="form-group">
					<label for="profileBlog">Blog</label>
					<input type="text" id="profileBlog" name="blog" class="form-control" placeholder="http:// 또는 https:// 포함 블로그 주소" value="@if(Input::old('blog')){{Input::old('blog')}}@endif" />
					<?php
					if($errors->has('blog')) {
						$msg='';
						switch($errors->first('blog')) {
							case 'max':
								$msg='블로그 주소는 256 글자 이하여야 합니다!';
								break;
							case 'url':
								$msg='블로그 주소 형식이 잘못됐습니다!';
								break;
						}
						printf('<p class="text-danger">%s</p>', $msg);
					}
					?>
				</div>

				<div class="form-group">
					<label for="profileIntro">Introduction</label>
					<textarea type="text" rows="5" id="profileIntro" name="intro" class="form-control" placeholder="자신을 소개할 말을 입력해주세요">@if(!empty($profile->intro)){{($profile->intro)}}@endif</textarea>
					<?php
					/*
					if($errors->has('intro')) {
						$msg='';
						switch($errors->first('intro')) {
							case 'max':
								$msg='자기소개는 256 글자 이하여야 합니다!';
								break;
						}
						printf('<p class="text-danger">%s</p>', $msg);
					}
					*/
					?>
				</div>

				<div>
					<button type="submit" class="btn btn-primary">업데이트</button>
				</div>				
				{{ Form::close() }}
			</div>				
		</div>
	</div>
</div><!--/.profile-editor-->
@stop

@section('footer_scripts')
@include('includes.upload-modal')
<script type="text/javascript">
var ProfileEditor={
	objx:{
		uploadCoverBtn:null,
		uploadProfileBtn:null,
		coverFigure:null,
		dataSection:null
	},
	endpoints:{
		uploadCover:"{{action('ProfileController@uploadCover')}}",
		uploadProfile:"{{action('ProfileController@uploadProfile')}}"
	},
	token:"{{csrf_token()}}",
	init:function() {
		this.objx.uploadCoverBtn=$('#uploadCoverBtn');
		this.objx.uploadProfileBtn=$('#uploadProfileBtn');
		this.objx.coverFigure=$('#profileCover');
		this.objx.dataSection=$('#profileData');

		this.objx.uploadCoverBtn.on('click', null, null, function() {
			UploadModal.launch('<div class="alert alert-info"><strong><span class="glyphicon glyphicon-ok"></span> Tip!</strong> 프로필 커버사진은 가로 1170px 이상이어야 합니다.</div>', function(file) {
				if(file instanceof File) {
					ProfileEditor.uploadCover(file);
				}
			}, null);
		});

		$('#uploadProfileBtn').on('click', null, null, function() {
			UploadModal.launch('<div class="alert alert-info"><strong><span class="glyphicon glyphicon-ok"></span> Tip!</strong> 프로필 사진은 가로, 세로 200px 이상의 정사각형이어야 합니다.</div>', function(file) {
				if(file instanceof File) {
					ProfileEditor.uploadProfile(file);
				}
			}, null);
		});
	},
	uploadCover:function(file) {
		//Disable button
		this.objx.uploadCoverBtn.prop('disabled', true);

		var formData=new FormData();
		formData.append('image', file);
		formData.append('_token', ProfileEditor.token);
		$.ajax({
			url: ProfileEditor.endpoints.uploadCover,
			type: "POST",
			data: formData,
			processData: false,  // tell jQuery not to process the data
			contentType: false,   // tell jQuery not to set contentType
			dataType:'json',
			success:function(response) {
				//console.log(response);
				if(typeof response==='object' && 'type' in response && 'data' in response) {
					if(response.type=='success') {
						ProfileEditor.objx.coverFigure.empty().removeClass('empty').append('<img src="'+response.data.url+'" alt="" /><button type="button" id="uploadCoverBtn" class="upload-cover-btn btn btn-primary">커버사진 업로드</button>');
					} else if(response.type=='error') {
						switch(response.data) {
							case 'image_width':
							AlertModal.launch('커버사진 가로 크기는 1170px 이상이어야 합니다!');
							break;
							case 'file_proc':
							AlertModal.launch('파일 처리 실패!', 'danger');
							break;
							case'no_file':
							AlertModal.launch('정상적인 업로드가 이루어지지 않았습니다!', 'danger');
							break;
							default:
							AlertModal.launch('알 수 없는 오류가 발생했습니다!', 'danger');
						}
					}
				} else {
					AlertModal.launch('정상적인 서버 응답을 받지 못했습니다!', 'danger');
				}
			},
			error:function() {
				AlertModal.launch('현재 서버와 통신이 불가능 합니다!', 'danger');
			},
			complete:function() {
				//Enable button
				ProfileEditor.objx.uploadCoverBtn.prop('disabled', false);
			}
		});
	},
	uploadProfile:function(file) {
		//Disable button
		this.objx.uploadProfileBtn.prop('disabled', true);

		var formData=new FormData();
		formData.append('image', file);
		formData.append('_token', ProfileEditor.token);
		$.ajax({
			url: ProfileEditor.endpoints.uploadProfile,
			type: "POST",
			data: formData,
			processData: false,  // tell jQuery not to process the data
			contentType: false,   // tell jQuery not to set contentType
			dataType:'json',
			success:function(response) {
				//console.log(response);
				if(typeof response==='object' && 'type' in response && 'data' in response) {
					if(response.type=='success') {
						ProfileEditor.objx.dataSection.css('background-image', 'url("'+response.data.url+'")');
					} else if(response.type=='error') {
						switch(response.data) {
							case 'image_size':
							AlertModal.launch('프로필 이미지는 가로, 세로 200px 이상의 정사각형이어야 합니다!');
							break;
							case 'file_proc':
							AlertModal.launch('파일 처리 실패!', 'danger');
							break;
							case'no_file':
							AlertModal.launch('정상적인 업로드가 이루어지지 않았습니다!', 'danger');
							break;
							default:
							AlertModal.launch('알 수 없는 오류가 발생했습니다!', 'danger');
						}
					}
				} else {
					AlertModal.launch('정상적인 서버 응답을 받지 못했습니다!', 'danger');
				}
			},
			error:function() {
				AlertModal.launch('현재 서버와 통신이 불가능 합니다!', 'danger');
			},
			complete:function() {
				//Enable button
				ProfileEditor.objx.uploadProfileBtn.prop('disabled', false);
			}
		});
	}
};//ProfileEditor{}

$(document).ready(function() {
	ProfileEditor.init();
});
</script>
@stop