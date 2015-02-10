<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
	protected $visible=array('id', 'nickname', 'profileImage');
	protected $appends = array('can_upload_snaps', 'is_staff', 'is_admin', 'is_superuser');

	/*Relationship definitions*/
	public function group() {
		return $this->belongsTo('Group');
	}

	public function fbAccount() {
		return $this->hasOne('FacebookAccount');
	}

	public function profileImage() {
		return $this->hasOne('ProfileImage');
	}

	public function profileCover() {
		return $this->hasOne('ProfileCover');
	}

	public function snaps() {
		return $this->hasMany('StreetSnap', 'user_id');
	}

	public function likes() {
		return $this->hasMany('UserLike', 'user_id');
	}

	public function editorials() {
		return $this->hasMany('EditorialPost');
	}

	/*Accessor definitions*/
	public function getCanUploadSnapsAttribute() {
		$authArray=array( 1, 3, 4, 5, 6, 8 );
		if( $this->group && in_array(intval($this->group->id), $authArray) ) {
			return true;
		} else {
			return false;
		}
	}

	public function getIsStaffAttribute() {
		$authArray=array( 4, 5, 6 );
		if( $this->group && in_array(intval($this->group->id), $authArray) ) {
			return true;
		} else {
			return false;
		}
	}

	public function getIsAdminAttribute() {
		$authArray=array( 5, 6 );
		if( $this->group && in_array(intval($this->group->id), $authArray) ) {
			return true;
		} else {
			return false;
		}
	}

	public function getIsSuperuserAttribute() {
		if( $this->group && intval($this->group->id)===6 ) {
			return true;
		} else {
			return false;
		}
	}

	public static function boot()
	{
		parent::boot();
		
		static::saving(function($user){
			
			if (!$user){
				return false;
			}
		});

		static::saving(function($fbAccount){
			
			if (!$fbAccount){
				return false;
			}
		});
		//setup event bindings.
	}

}
