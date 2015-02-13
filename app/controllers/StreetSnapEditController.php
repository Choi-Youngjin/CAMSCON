<?php

class StreetSnapEditController extends BaseController {

	public function showStarter() {
		//Get drafts
		$drafts=Auth::user()->snaps()->where('status','=','draft')->orderBy('created_at', 'DESC')->get();
		ViewData::add('drafts', $drafts);

		//Get published
		$published=Auth::user()->snaps()->with('meta')->where('status','=','published')->orderBy('created_at', 'DESC')->get();
		ViewData::add('published', $published);
		

		return View::make('front.streetsnap.starter', ViewData::get());
	}

	public function showEditor($id=null) {
		$snap=null;
		if($id) {
			$snap=$this->loadStreetSnap($id);
		}

		if(empty($snap)) {
			$snap=new StreetSnap;
			$snap->user_id=Auth::user()->id;
			$snap->status='draft';
			$snap->save();
			return Redirect::to(action('StreetSnapEditController@showEditor', $snap->id));
		}

		//Get drafts
		$drafts=Auth::user()->snaps()->where('status','=','draft')->orderBy('created_at', 'DESC')->get();
		ViewData::add('drafts', $drafts);

		//Get item categories
		$categories=FashionItemCategory::orderBy('id','asc')->get();

		$tree_temp=array();

		foreach($categories as $category) {
			if($category->parent_id==0) {
				$parentElement=new stdClass();
				$parentElement->model=$category;
				$parentElement->children=array();
				$tree_temp[$category->id]=$parentElement;
			}
		}

		foreach($categories as $category) {
			if($category->parent_id!=0) {
				$tree_temp[$category->parent_id]->children[]=$category;
			}
		}

		$tree_output=array();
		foreach ($tree_temp as $element) {
			$tree_output[]=$element;
		}
		ViewData::add('itemCategories', $tree_output);

		if($snap) {
			ViewData::add('snap', $snap);
			return View::make('front.streetsnap.editor', ViewData::get());
		} else {
			return Redirect::to('error/not-found');
		}
	}//showEditor()

	public function savePin() {
		$input=Input::only('id', 'streetsnap_id', 'item_id', 'brand_id', 'link', 'top', 'left');

		$validationRules=array(
			'id'=>array('sometimes', 'exists:pin_tags,id'),
			//'streetsnap_id'=>array('required', 'exists:street_snaps,id,user_id,'.Auth::user()->id),
			'streetsnap_id'=>array('required', 'exists:street_snaps,id'),
			'item_id'=>array('required', 'exists:fashion_item_categories,id'),
			'brand_id'=>array('required', 'exists:fashion_brands,id'),
			'link'=>array('sometimes', 'url'),
			'top'=>array('required', 'regex:/^\d{1,5}(\.\d{1,2})?$/'),
			'left'=>array('required', 'regex:/^\d{1,5}(\.\d{1,2})?$/')
		);

		$messages=array(
			'required'=>'required',
			'exists'=>'exists',
			'url'=>'url',
			'regex'=>'regex'
		);

		$validator=Validator::make($input, $validationRules, $messages);

		$response=new stdClass();

		if($validator->passes()) {
			$snap=StreetSnap::find($input['streetsnap_id']);

			if($snap->user_id===Auth::user()->id || Auth::user()->is_staff) {
				if(!empty($input['id'])) {
					$pin=PinTag::find($input['id']);
				} else {
					$pin=new PinTag;
				}
				$pin->top=$input['top'];
				$pin->left=$input['left'];
				$pin->brand_id=$input['brand_id'];
				$pin->item_id=$input['item_id'];

				if(!empty($input['link'])) {
					$link=new PinLink;
					$link->pin_link_type='user';
					$link->title='사용자 입력 링크';
					$link->url=$input['link'];
				}

				DB::beginTransaction();
				try {
					if($snap->pins()->save($pin)===false) {
						throw new Exception("Error saving pin");
					}

					if(isset($link)) {
						if($pin->links()->delete()===false) {
							throw new Exception("Error deleting old pin link");
						}

						if($pin->links()->save($link)===false) {
							throw new Exception("Error saving pin link");
						}
					}

					DB::commit();

					$response->type='success';
					$response->data=$pin->id;
				} catch(Exception $e) {
					DB::rollback();
					Log::error($e);
					$response->type='error';
					$response->data='db_error';
				}	
			} else {
				$response->type='error';
				$response->data='permission_error';
			}
		} else {
			$msgs=$validator->messages();

			if($msgs->has('streetsnap_id') && $msgs->first('streetsnap_id')=='exists') {
				$response->type='error';
				$response->data='no_pin';
			} elseif($msgs->has('link') && $msgs->first('link')=='url') {
				$response->type='error';
				$response->data='url_error';
			} elseif ($msgs->has('brand_id') && $msgs->first('brand_id')=='required') {
				$response->type='error';
				$response->data='no_brand';
			} else {
				$response->type='error';
				$response->data='invalid_request';
			}
		}

		return Response::json($response);
	}//savePin()

	public function deletePin() {
		$input=Input::only('id', 'streetsnap_id');

		$validationRules=array(
			'id'=>array('required', 'exists:pin_tags,id'),
			'streetsnap_id'=>array('required', 'exists:street_snaps,id'),
		);

		$messages=array(
			'required'=>'required',
			'exists'=>'exists',
		);

		$validator=Validator::make($input, $validationRules, $messages);

		$response=new stdClass();

		if($validator->passes()) {
			$snap=StreetSnap::find($input['streetsnap_id']);
			if($snap->user_id===Auth::user()->id || Auth::user()->is_staff) {
				DB::beginTransaction();
				try {
					$pin=PinTag::find($input['id']);
					$pin_id=$pin->id;

					if($pin->links()->delete()===false) {
						throw new Exception("Error deleting pin links");
					}

					if($pin->delete()===false) {
						throw new Exception("Error deleting pin");
					}

					DB::commit();

					$response->type='success';
					$response->data=$pin_id;
				} catch(Exception $e) {
					DB::rollback();
					Log::error($e);
					$response->type='error';
					$response->data='db_error';
				}
			} else {
				$response->type='error';
				$response->data='permission_error';
			}
		} else {
			$msgs=$validator->messages();

			if($msgs->has('streetsnap_id') && $msgs->first('streetsnap_id')=='exists') {
				$response->type='error';
				$response->data='no_snap';
			} elseif($msgs->has('id') && $msgs->first('id')=='exists') {
				$response->type='error';
				$response->data='no_pin';
			} else {
				$response->type='error';
				$response->data='invalid_request';
			}
		}

		return Response::json($response);
	}//deletePin()

	public function uploadPrimary() {
		$response=new stdClass;
		$response->type=null;
		$response->data=null;

		//Check StreetSnap existence and ownership
		$input=Input::only('streetsnap_id');

		$snap=$this->loadStreetSnap(intval($input['streetsnap_id']));
		if($snap) {
			if(Input::hasFile('image')) {
				$img=new StreetSnapPrimary;
				try {
					$img->setUploadedFile(Input::file('image'));
					$img->restrictWidth(670);

					$oldImg=null;
					if($snap->primary) {
						$oldImg=$snap->primary->id;
					}

					if($snap->primary()->save($img)) {
						if($oldImg) {
							if($snap->pins()->count()) {
								$snap->pins->each(function($pin) {
									if($pin->links()->count()) {
										$pin->links->each(function($link) {
											$link->delete();
										});
									}

									$pin->delete();
								});
							}
							
							$oldPrimary=StreetSnapPrimary::find($oldImg);
							$oldPrimary->delete();
						}
						$response->type='success';
						$response->data=$img;
					} else {
						throw new Exception("Failed to save file.");
					}
				} catch(Exception $e) {Log::error($e);
					$response->type='error';
					$response->data='file_proc';
				}
			} else {
				$response->type='error';
				$response->data='no_file';
			}
		} else {
			if($snap===null) {
				$response->type='error';
				$response->data='not_found';
			} else {
				$response->type='error';
				$response->data='not_owner';
			}
		}

		return Response::json($response);
	}//uploadPrimary()

	public function uploadAttachment() {
		$response=new stdClass;
		$response->type=null;
		$response->data=null;

		//Check StreetSnap existence and ownership
		$input=Input::only('streetsnap_id');

		$snap=$this->loadStreetSnap(intval($input['streetsnap_id']));
		if($snap) {
			if(Input::hasFile('image')) {
				$img=new StreetSnapAttachment;
				try {
					$img->setUploadedFile(Input::file('image'));
					$img->restrictWidth(670);

					if($snap->primary()->save($img)) {
						$response->type='success';
						$response->data=$img;
					} else {
						throw new Exception("Failed to save file.");
					}
				} catch(Exception $e) {Log::error($e);
					$response->type='error';
					$response->data='file_proc';
				}
			} else {
				$response->type='error';
				$response->data='no_file';
			}
		} else {
			if($snap===null) {
				$response->type='error';
				$response->data='not_found';
			} else {
				$response->type='error';
				$response->data='not_owner';
			}
		}

		return Response::json($response);
	}//uploadAttachment()

	public function deleteAttachment() {
		$response=new stdClass;
		$response->type=null;
		$response->data=null;

		//Check StreetSnap existence and ownership
		$input=Input::only('streetsnap_id', 'attachment_id');

		$snap=$this->loadStreetSnap(intval($input['streetsnap_id']));
		if($snap) {
			$attachment=StreetSnapAttachment::find($input['attachment_id']);
			if($attachment) {
				if($attachment->delete()) {
					$response->type='success';
					$response->data=$input['attachment_id'];
				} else {
					$response->type='error';
					$response->data='file_proc';
				}
			} else {
				$response->type='error';
				$response->data='no_attachment';
			}
		} else {
			if($snap===null) {
				$response->type='error';
				$response->data='not_found';
			} else {
				$response->type='error';
				$response->data='not_owner';
			}
		}

		return Response::json($response);
	}

	public function publishPost() {
		Input::flash();

		Validator::extend('meta_exists', function($attribute, $value, $parameters) {
			switch($parameters[0]) {
				case 'CampusMeta':
					$meta=CampusMeta::find($value);
					break;
				case 'StreetMeta':
					$meta=StreetMeta::find($value);
					break;
				case 'FestivalMeta':
					$meta=FestivalMeta::find($value);
					break;
				case 'ClubMeta':
					$meta=ClubMeta::find($value);
					break;
				case 'FashionWeekMeta':
					$meta=FashionWeekMeta::find($value);
					break;
				case 'BlogMeta':
					$meta=BlogMeta::find($value);
					break;
				case 'MagazineMeta':
					$meta=MagazineMeta::find($value);
					break;
				default:
					return false;
			}

			if($meta) {
				return true;
			} else {
				return false;
			}
		});

		$input=Input::only(
			'streetsnap_id',
			'name',
			'birth_year',
			'meta_type',
			'meta_id',
			'affiliation',
			'gender',
			'subject_comment',
			'photographer_comment',
			'season'
		);

		$validationRules=array(
			'streetsnap_id'=>array('required', 'exists:street_snaps,id'),
			'name'=>array('required'),
			'birth_year'=>array('sometimes', 'date_format:Y'),
			'meta_type'=>array('required', 'in:CampusMeta,StreetMeta,FestivalMeta,ClubMeta,FashionWeekMeta,BlogMeta,MagazineMeta'),
			'meta_id'=>array('required', 'meta_exists:'.$input['meta_type']),
			/*'affiliation',*/
			'gender'=>array('required', 'in:male,female'),
			/*'subject_comment',*/
			/*'photographer_comment',*/
			'season'=>array('required', 'in:S/S,F/W')
		);

		$messages=array(
			'required'=>'required',
			'date_format'=>'date_format',
			'in'=>'in',
			'meta_exists'=>'meta_exists'
		);

		$validator=Validator::make($input,$validationRules,$messages);

		if($validator->passes()) {
			$snap=StreetSnap::with('primary', 'user')->find($input['streetsnap_id']);

			//Check ownership or authorization
			if($snap->user->id===Auth::user()->id || Auth::user()->is_superuser) {
				if($snap->primary) {
					$snap->name=$input['name'];
					if(!empty($input['birth_year'])) {
						$snap->birthyear=$input['birth_year'];
					}
					if(!empty($input['affiliation'])) {
						$snap->affiliation=$input['affiliation'];
					}
					$snap->gender=$input['gender'];
					$snap->subject_comment=$input['subject_comment'];
					$snap->photographer_comment=$input['photographer_comment'];
					$snap->season=$input['season'];
					$snap->meta_type=$input['meta_type'];
					$snap->meta_id=$input['meta_id'];

					$snap->status='published';

					if($snap->save()) {
						return Redirect::to(action('StreetSnapEditController@showStarter'))->with('proc_result', 'success');
					} else {
						return Redirect::back()->with('proc_result', 'db_error');
					}
				} else {
					return Redirect::back()->with('proc_result', 'primary_missing');
				}
			} else {
				return Redirect::back()->with('proc_result', 'auth_error');
			}
		} else {
			return Redirect::back()->withErrors($validator);
		}

	}

	public function deletePost() {
		$input=Input::only('id');

		$validationRules=array(
			'id'=>array('required', 'exists:street_snaps,id,user_id,'.Auth::user()->id)
		);

		$validator=Validator::make($input, $validationRules);

		if($validator->passes()) {
			$snap=StreetSnap::with('primary', 'attachments', 'pins', 'pins.links', 'likes')->find($input['id']);
			
			if($snap->primary) {
				$snap->primary->delete();
			}

			if($snap->attachments) {
				$snap->attachments->each(function($attachment) {
					$attachment->delete();
				});
			}

			if($snap->pins) {
				$snap->pins->each(function($pin) {
					if($pin->links) {
						$pin->links->each(function($link) {
							$link->delete();
						});
					}
					$pin->delete();
				});
			}

			if($snap->likes) {
				$snap->likes->each(function($like) {
					$like->delete();
				});
			}

			$snap->delete();

			return Redirect::to(action('StreetSnapEditController@showStarter'))->with('proc_result', 'delete_success');
		} else {
			return Redirect::back()->with('proc_error', 'no_post');
		}
	}

	private function loadStreetSnap($id=0) {
		$snap=StreetSnap::with('user', 'primary', 'attachments', 'pins', 'pins.brand', 'pins.itemCategory', 'pins.links', 'meta')->find(intval($id));
		if($snap) {
			if(is_object($snap->user) && (intval($snap->user->id)===intval(Auth::user()->id) || Auth::user()->is_staff)) {
				return $snap;
			} else {
				return false;
			}
		} else {
			return null;
		}
	}//getStreetSnap()

	public function getMetaJson($query) {
		$response=new stdClass();

		$response->campus_meta=new stdClass();
		$response->campus_meta->matches=true;
		$response->campus_meta->data=CampusMeta::where('name_ko', 'LIKE', '%'.$query.'%')->orWhere('name_en', 'LIKE', '%'.$query.'%')->get(array('id','name_en','name_ko'));

		$response->street_meta=new stdClass();
		$response->street_meta->matches=true;
		$response->street_meta->data=StreetMeta::where('name_ko', 'LIKE', '%'.$query.'%')->orWhere('name_en', 'LIKE', '%'.$query.'%')->get(array('id','name_en','name_ko'));

		$response->festival_meta=new stdClass();
		$response->festival_meta->matches=true;
		$response->festival_meta->data=FestivalMeta::where('name_ko', 'LIKE', '%'.$query.'%')->orWhere('name_en', 'LIKE', '%'.$query.'%')->get(array('id','name_en','name_ko'));

		$response->club_meta=new stdClass();
		$response->club_meta->matches=true;
		$response->club_meta->data=ClubMeta::where('name_ko', 'LIKE', '%'.$query.'%')->orWhere('name_en', 'LIKE', '%'.$query.'%')->get(array('id','name_en','name_ko'));

		$response->fashionweek_meta=new stdClass();
		$response->fashionweek_meta->matches=true;
		$response->fashionweek_meta->data=FashionWeekMeta::where('name_ko', 'LIKE', '%'.$query.'%')->orWhere('name_en', 'LIKE', '%'.$query.'%')->get(array('id','name_en','name_ko'));

		//Blog meta (Requires permission check)
		$response->blog_meta=new stdClass();
		$response->blog_meta->matches=true;
		$response->blog_meta->data=array();
		if(Auth::user()->can_upload_snaps) {
			$response->blog_meta->data=BlogMeta::where('name', 'LIKE', '%'.$query.'%')->get(array('id','name'));
		}

		$response->magazine_meta=new stdClass();
		$response->magazine_meta->matches=true;
		$response->magazine_meta->data=MagazineMeta::where('name_ko', 'LIKE', '%'.$query.'%')->orWhere('name_en', 'LIKE', '%'.$query.'%')->get(array('id','name_en','name_ko'));
		
		return Response::json($response);
	}

}
