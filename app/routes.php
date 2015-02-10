<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*Front-end StreetSnap Routes*/
//Single View for men/ladies
/*
Route::get('{category}/{id}',
	array('uses'=>"StreetSnapController@getSingle")
)->where(array('category'=>'men|ladies', 'id'=>'^[0-9]+$'));
*/

Route::group(array('before' => 'front'), function() {

	//List View
	Route::get('{category}/{slug?}/{ordering?}',
		array('uses'=>"StreetSnapController@getList")
	)->where(array('category'=>'all|campus|street|brand|fashion-week|festival|club|filter|blog|magazine', 'ordering'=>'new|hot'));

	Route::get('/', array('uses'=>"StreetSnapController@getList"));

	//Single View for campus,street,brand,fashion-week,festival,club,blog
	Route::get('{category}/{slug}/{id}',
		array('before'=>'restricted-page', 'uses'=>"StreetSnapController@getSingle")
	)->where(array('category'=>'filter|campus|street|brand|fashion-week|festival|blog|magazine', 'id'=>'^[0-9]+$'));

	//Profile View
	Route::get('profile/{id}', array('uses'=>'ProfileController@showProfile'));
	Route::get('profile/{id}/{filter}/more', array('uses'=>'ProfileController@loadMoreSnaps'));


	/*Front-end Auth Routes*/
	//Login & Logout
	Route::post('auth/user/login/email', array('before'=>'csrf', 'uses'=>'UserController@loginWithEmail'));
	Route::post('auth/user/login/fb', array('before'=>'csrf', 'uses'=>'UserController@loginWithFB'));
	Route::get('auth/user/logout', array('uses'=>'UserController@logoutUser'));
	Route::get('user/userbox', array('uses'=>'UserController@userBoxTemplate'));

	//Signup
	//Route::get('user/signup', array('uses'=>'UserController@showSignup'));
	Route::post('user/signup', array('before'=>'csrf', 'uses'=>'UserController@signupUser'));

	//Password reset
	Route::get('auth/user/forgot-password', array('uses'=>'RemindersController@getRemind'));
	Route::get('auth/user/reset-password/{token}', array('uses'=>'RemindersController@getReset'));
	Route::post('auth/user/reset-password', array('before'=>'csrf', 'uses'=>'RemindersController@postReset'));

	//Login wall
	Route::get('auth/login-required', array('uses'=>'UserController@showLoginRequired'));


	/*Style StreetSnap editor routes*/
	Route::get('post/street-snap/starter', array('before'=>'auth.can_upload_snaps', 'uses'=>'StreetSnapEditController@showStarter'));
	Route::get('post/street-snap/{id?}', array('before'=>'auth.can_upload_snaps', 'uses'=>'StreetSnapEditController@showEditor'))->where('id','[0-9]+');
	Route::post('post/street-snap/upload/primary', array('before'=>'auth.can_upload_snaps|csrf', 'uses'=>'StreetSnapEditController@uploadPrimary'));
	Route::post('post/street-snap/upload/attachment', array('before'=>'auth.can_upload_snaps|csrf', 'uses'=>'StreetSnapEditController@uploadAttachment'));
	Route::post('post/street-snap/delete/attachment', array('before'=>'auth.can_upload_snaps|csrf', 'uses'=>'StreetSnapEditController@deleteAttachment'));
	Route::get('post/street-snap/data/brands/{query?}', array('uses'=>'BrandsController@jsonList'));
	Route::post('post/street-snap/save/pin', array('before'=>'auth.can_upload_snaps|csrf', 'uses'=>'StreetSnapEditController@savePin'));
	Route::post('post/street-snap/delete/pin', array('before'=>'auth.can_upload_snaps|csrf', 'uses'=>'StreetSnapEditController@deletePin'));
	Route::post('post/street-snap/publish', array('before'=>'auth.can_upload_snaps|csrf', 'uses'=>'StreetSnapEditController@publishPost'));
	Route::post('post/street-snap/delete', array('before'=>'auth.can_upload_snaps|csrf', 'uses'=>'StreetSnapEditController@deletePost'));
	Route::get('post/street-snap/data/meta/{query?}', array('uses'=>'StreetSnapEditController@getMetaJson'));


	/*New Snap Editor Development Routes*/
	Route::get('neweditor/starter', array('before'=>'auth.is_superuser', 'uses'=>'StreetSnapEditor@showStarter'));
	Route::get('neweditor/editor/{id?}', array('before'=>'auth.is_superuser', 'uses'=>'StreetSnapEditor@showEditor'));


	/*Profile Edit routes*/
	Route::get('profile-edit', array('before'=>'auth', 'uses'=>'ProfileController@showEditor'));
	Route::post('profile-edit/cover', array('before'=>'auth|csrf', 'uses'=>'ProfileController@uploadCover'));
	Route::post('profile-edit/image', array('before'=>'auth|csrf', 'uses'=>'ProfileController@uploadProfile'));
	Route::post('profile-edit/save', array('before'=>'auth|csrf', 'uses'=>'ProfileController@saveProfile'));


	/*Like routes*/
	Route::post('user/action/like', array('before'=>'auth', 'uses'=>'LikeController@procLike'));
	Route::post('like/get/snaps', array('before'=>'auth|csrf', 'uses'=>'LikeController@getCurrentUserSnapLikes'));

	/*Comment routes*/
	Route::get('user/action/comment/get', array('uses'=>'CommentController@getComments'));
	Route::post('user/action/comment/save', array('before'=>'auth|csrf', 'uses'=>'CommentController@saveComment'));
	Route::post('user/action/comment/delete', array('before'=>'auth|csrf', 'uses'=>'CommentController@deleteComment'));

	/*Inspirer Register feature*/
	Route::get('forms/inspirer-register', array('uses'=>'InspirerRegisterController@showRegister'));
	Route::post('forms/inspirer-register', array('before'=>'csrf', 'uses'=>'InspirerRegisterController@postRegister'));

	/*Contact Form*/
	Route::get('forms/user-feedback', array('uses'=>'UserFeedbackController@showForm'));
	Route::post('forms/user-feedback/post', array('before'=>'csrf', 'uses'=>'UserFeedbackController@postFeedback'));

	/*Legal Documents*/
	Route::get('legal/terms-of-use', function() {
		return View::make('legal/terms-of-use', ViewData::get());
	});

	Route::get('legal/privacy-policy', function() {
		return View::make('legal/privacy-policy', ViewData::get());
	});

	/*Recruiting pages*/
	Route::get('recruiting/season-7', function() {
		return View::make('front.recruiting.season-7', ViewData::get());
	});

	/*Simple Pictorial routes*/
	Route::get('editorials/pictorials', function() {
		$pictorials=SimplePictorial::with('attachments')->where('status', '=', 'published')->orderBy('id', 'ASC')->get();
		ViewData::add('pictorials', $pictorials);

		return View::make('front.editorials.pictorials.list', ViewData::get());
	});

	Route::get('editorials/pictorials/{entry_id}', function($entry_id=null) {
		$pictorial=SimplePictorial::with('attachments')->find($entry_id);

		if($pictorial) {
			ViewData::add('pictorial', $pictorial);
		} else {
			App::abort(404);
		}

		return View::make('front.editorials.pictorials.single', ViewData::get());
	});

});//Front-end route group


/*Admin Routes*/
Route::get('admin', function() {return Redirect::to('admin/dashboard');});
Route::group(array('prefix' => 'admin', 'before'=>'auth.is_admin'), function() {

	Route::get('dashboard', array('uses'=>'AdminController@showDashboard'));

	/*Admin Brands*/
	Route::get('brands', array('uses'=>'BrandsController@showDashboard'));
	Route::get('brands/edit/{brand_id}', array('uses'=>'BrandsController@showEditor'));
	Route::post('brands/save', array('uses'=>'BrandsController@saveBrand'));
	Route::post('brands/delete', array('uses'=>'BrandsController@deleteBrand'));

	/*Admin Categories*/
	Route::get('categories', array('uses'=>'CategoriesController@showDashboard'));
	Route::get('categories/edit/{category_id}', array('uses'=>'CategoriesController@showEditor'));
	Route::post('categories/save', array('uses'=>'CategoriesController@saveCategory'));
	Route::post('categories/change-parent', array('uses'=>'CategoriesController@changeParent'));
	Route::post('categories/delete', array('uses'=>'CategoriesController@deleteCategory'));

	/*Admin User Groups*/
	Route::get('user-groups/{queryType?}/{field?}', array('uses'=>'GroupsController@showUsers'));
	Route::post('user-groups/delete-checked', array('filter'=>'csrf', 'uses'=>'GroupsController@deleteUsers'));
	//Route::post('user-groups/copy-checked', array('filter'=>'csrf', 'uses'=>'GroupsController@copyUsers'));
	Route::post('user-groups/move-checked', array('filter'=>'csrf', 'uses'=>'GroupsController@moveUsers'));

	/*Override user*/
	Route::get('override/{user_id}', array('uses'=>'AdminController@overrideUser'));

	/*Inspirer Register Admin*/
	Route::get('inspirer-register', array('uses'=>'InspirerRegisterController@showAdmin'));
	Route::post('inspirer-register/change-status/{status}', array('filter'=>'csrf', 'uses'=>'InspirerRegisterController@changeStatus'));
	Route::post('inspirer-register/delete', array('filter'=>'csrf', 'uses'=>'InspirerRegisterController@deleteForms'));
	
	/*User Feedback*/
	Route::get('user-feedback', array('uses'=>'UserFeedbackController@showAdmin'));
	Route::post('user-feedback/delete', array('before'=>'csrf', 'uses'=>'UserFeedbackController@deleteFeedback'));

	/*Pictorial Admin routes*/
	Route::get('editorials/pictorials', array('uses'=>'PictorialEditController@showList'));
	Route::get('editorials/pictorials/edit/{post_id?}', array('uses'=>'PictorialEditController@showEditor'));
	Route::post('editorials/pictorials/save', array('before'=>'csrf', 'uses'=>'PictorialEditController@saveEntry'));
	Route::post('editorials/pictorials/delete', array('before'=>'csrf', 'uses'=>'PictorialEditController@deleteEntry'));
	Route::post('editorials/pictorials/attachment/upload', array('before'=>'csrf', 'uses'=>'PictorialEditController@uploadAttachment'));
	Route::post('editorials/pictorials/attachment/delete', array('before'=>'csrf', 'uses'=>'PictorialEditController@deleteAttachment'));
});

/*Admin Auth Routes*/
Route::get('auth/admin/login', array('before'=>'login-wall','uses'=>'AdminController@showLogin'));
Route::post('auth/admin/login/fb', array('before'=>'csrf','uses'=>'AdminController@loginWithFB'));
Route::post('auth/admin/login/email', array('before'=>'csrf','uses'=>'AdminController@loginWithEmail'));
Route::get('auth/admin/send-reset', array('uses'=>'RemindersController@getAdminRemind'));
Route::post('auth/admin/send-reset', array('uses'=>'RemindersController@postRemind'));
Route::get('auth/admin/logout', array('uses'=>'AdminController@logoutUser'));


/*Error Routes*/
Route::group(array('before'=>'front', 'prefix' => 'error'), function() {

	Route::get('not-authorized', array('uses'=>'ErrorPagesController@notAuthorized'));
	Route::get('login-required', array('uses'=>'ErrorPagesController@loginRequired'));
	Route::get('not-found', array('uses'=>'ErrorPagesController@notFound'));

});


/*Mockup Routes*/
Route::get('mockup/main', function() {

	$photos=array(
		'mockup-assets/sample-content/1.jpg',
		'mockup-assets/sample-content/2.jpg',
		'mockup-assets/sample-content/3.jpg',
		'mockup-assets/sample-content/4.jpg',
		'mockup-assets/sample-content/5.jpg',
		'mockup-assets/sample-content/6.jpg',
		'mockup-assets/sample-content/7.jpg',
		'mockup-assets/sample-content/8.jpg',
		'mockup-assets/sample-content/9.jpg'
	);

	$icons=array();

	foreach($photos as $photo) {
		$icon=new stdClass();
		$icon->photo=asset($photo);
		$icon->name='이해인';
		$icon->meta='한국외국어대학교';
		$icon->author=new stdClass();
		$icon->author->photo=asset('mockup-assets/sample-content/author.jpg');
		$icon->author->name='캠스콘';
		$icons[]=$icon;
	}

	return View::make('mockup.main', array('icons'=>$icons));
});

Route::get('mockup/detail', function() {
	return View::make('mockup.detail');
});


/*Dev Routes*/

/*Query liked
Route::get('test/liked', function() {
	$s=StreetSnap::find(1);
	dd($s->liked);
});
*/

/*Transaction error handling
Route::get('test/transaction', function() {
	try {
		$proc=DB::transaction(function() {
			throw new Exception("Error Processing Request", 1);
		});
	} catch(Exception $e) {
		$proc=false;
	}
	dd($proc);
});
*/

/*HOT query optimization
Route::get('test/query', function() {
	$snaps=StreetSnap::with('user.profileImage', 'primary', 'meta', 'liked')
		->from(DB::raw("(select * from street_snaps where created_at>='2014-10-15 00:00:00' and status='published') as T1"))
		->orderBy('cached_total_likes', 'DESC')
		->orderBy('created_at', 'DESC')
		->paginate(9);

	dd(DB::getQueryLog());
});
*/
