<?php
use Facebook\FacebookSession;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

class UserController extends BaseController {

	public function showLoginRequired() {
		if(!Session::has('intended')) {
			Session::put('intended', url('/'));
		}
		return View::make('front.auth.login-required', ViewData::get());
	}//showSignupPage()

	public function signupUser() {
		$response=new \stdClass();
		$input=Input::only('email', 'nickname', 'password', 'password_confirmation', 'gender', 'remember');

		$validationRules=array(
			'email'=>array('required', 'email', 'unique:users,email'),
			'nickname'=>array('required', 'min:2', 'unique:users,nickname'),
			'password'=>array('required', 'min:8', 'confirmed'),
			'gender'=>array('required', 'in:male,female')
		);

		$messages=array(
			'required'=>':attribute_required',
			'email'=>':attribute_email',
			'unique'=>':attribute_unique',
			'min'=>':attribute_min',
			'confirmed'=>':attribute_confirmed',
			'in'=>':attribute_in'
		);

		$validator=Validator::make($input, $validationRules, $messages);

		if($validator->passes()) {
			$user=new User;
			$user->email=$input['email'];
			$user->password=Hash::make($input['password']);
			$user->nickname=$input['nickname'];
			$user->gender=$input['gender'];

			if($user->save()) {
				$creds=array(
					'email'=>$input['email'],
					'password'=>$input['password'],
				);

				if($input['remember']) {
					$remember=true;
				} else {
					$remember=false;
				}

				if(Auth::attempt($creds, $remember)) {
					$response->type='success';
					$response->msg=$this->userBoxTemplate();
					$response->json=$user->toJson();
				} else {
					$response->type='error';
					$response->msg='hash_error';
				}
			} else {
				$response->type='error';
				$response->msg='db_error';
			}
		} else {
			$response->type='error';
			$response->msg=$validator->messages()->first();
		}

		return Response::json($response);
	}//signupUser()

	public function loginWithFB() {
		if(Auth::check()) {
			Auth::logout();
		}

		//Setup response obj
		$response=new stdClass();

		//Init FB SDK
		FacebookSession::setDefaultApplication(Config::get('app.fb_app_id'), Config::get('app.fb_app_secret'));

		//Validate login
		$loginHelper = new FacebookJavaScriptLoginHelper();

		$session=null;

		try {
			$session=$loginHelper->getSession();
		} catch(FacebookRequestException $ex) {
			// When Facebook returns an error
			$response->type='error';
			$response->msg='fb_api_error';
		} catch(\Exception $ex) {
			// When validation fails or other local issues
			$response->type='error';
			$response->msg='fb_validation_error';
		}

		if($session) {// Logged in
			//Get fb user id
			$fbUserId=$session->getSessionInfo()->asArray()['user_id'];
			//Search for associated user
			$user = User::whereHas('fbAccount', function($query) use($fbUserId) {
				$query->where('fb_id','=',$fbUserId);
			})->first();
			if($user) {
				//User account associated with the fb user id exists. Login user.
				Auth::login($user);

				$response->type='success';
				$response->msg=$this->userBoxTemplate();
				$response->json=$user->toJson();
			} else {
				//The fb user does not have an account.
				//Create user with fb account
				try {
					$user_profile = (new FacebookRequest(
						$session, 'GET', '/me'
					))->execute()->getGraphObject(GraphUser::className());

					$fbPicture = (new FacebookRequest(
						$session, 'GET', '/me/picture', 
						array(
							'redirect'=>false,
							'type'=>'large',
							'width'=>200,
							'height'=>200
						)
					))->execute()->getGraphObject();

					$user=new User;
					$user->email=$user_profile->getProperty('email');
					$user->password=null;
					$user->nickname=$user_profile->getName();
					$gender=$user_profile->getProperty('gender');
					if($gender=='male') {
						$user->gender='male';
					} elseif($gender=='female') {
						$user->gender='female';
					} else {
						$user->gender=null;
					}

					if($user->save()) {
						$fbAccount=new FacebookAccount;
						$fbAccount->user_id=$user->id;
						$fbAccount->fb_id=$user_profile->getId();
						$fbAccount->email=$user_profile->getProperty('email');
						$fbAccount->name=$user_profile->getName();
						$fbAccount->first_name=$user_profile->getFirstName();
						$fbAccount->last_name=$user_profile->getLastName();
						$fbAccount->link=$user_profile->getLink();
						$fbAccount->gender=$user_profile->getProperty('gender');
						$fbAccount->locale=$user_profile->getProperty('locale');

						if($fbPicture->getProperty('is_silhouette')===false) {
							$profileImage=new ProfileImage;
							try {
								$profileImage->user_id=$user->id;
								$profileImage->setRemoteFile($fbPicture->getProperty('url'));
							} catch(Exception $e) {
								$profileImage=null;
							}
							
						}
						
						DB::beginTransaction();
						try {
							$fbAccount->save();
							if(is_object($profileImage)) {
								$profileImage->save();
							}

							DB::commit();

							Auth::login($user);

							$response->type='success';
							$response->msg=$this->userBoxTemplate();
							$response->json=$user->toJson();
						} catch(Exception $e) {//Log::info('transaction failed');
							DB::rollback();
							$user->forceDelete();
							$response->type='error';
							$response->msg='db_error';
						}
					} else {
						$response->type='error';
						$response->msg='db_error';
					}
				} catch(FacebookRequestException $e) {
					$response->type='error';
					$response->msg='fb_profile_error';
				}
			}
		}

		return Response::json($response);
	}//loginWithFB()

	public function loginWithEmail() {
		if(Auth::check()) {
			Auth::logout();
		}

		$input=Input::only('email','password','remember');

		$creds=array(
			'email'=>$input['email'],
			'password'=>$input['password'],
		);

		if($input['remember']) {
			$remember=true;
		} else {
			$remember=false;
		}

		$response=new \stdClass();

		if (Auth::attempt($creds, $remember)) {
			$response->type='success';
			$response->msg=$this->userBoxTemplate();
			$response->json=Auth::user()->toJson();
		} else {
			$response->type='error';
		}

		return Response::json($response);
	}//loginWithEmail()

	public function logoutUser() {
		Auth::logout();
		return Redirect::back();
	}//logoutUser()

	private function userBoxTemplate() {
		$userBox=View::make('includes.user-box')->render();
		return $userBox;
	}//userBoxTemplate()

}
