function PinEngine() {
	this.max_pins=9;
	this.pins=[];

	this.init=function(container, list) {
		this.container=container;
		this.img=container.siblings('img');
		this.pinContainer=container.find('pin-container');
		this.list=list;
	};

	this.loadPins=function(pins) {
		var plen=pins.length;
		for(var i=0;i<plen;i++) {
			var pin=pins[i];
			var newPin=new PinObject(parseFloat(pin.left), parseFloat(pin.top));
			newPin.init();
			newPin.id=pin.id;
			newPin.item.item_id=pin.item_category.id;
			newPin.item.item_name=pin.item_category.name;
			newPin.brand.brand_id=pin.brand.id;
			newPin.brand.brand_name=pin.brand.name;
			var linklen=pin.links.length;
			for(var k=0;k<linklen;k++) {
				if(pin.links[k].pin_link_type=='user') {
					newPin.link=pin.links[k].url;
					break;
				}
			}
			this.pins.push(newPin);
		}
		this.renderPins();
	};

	this.addPin=function(x, y) {
		if(this.pins.length<this.max_pins) {
			var newPin=new PinObject(x, y);
			newPin.init();
			var pinsLength=this.pins.push(newPin);
			this.renderPins();
			PinEditor.launch(newPin, function(data) {
				if(data.brand) {
					newPin.brand=data.brand;
				}
				newPin.item=data.item;
				newPin.link=data.link;
				PrimaryEditor.pinEngine.renderPins();
				PrimaryEditor.pinEngine.savePin(newPin);
			});
		}
	};

	this.removePin=function(id) {
		var pinsLength=this.pins.length;
		for(var i=0;i<pinsLength;i++) {
			if(this.pins[i].id==id) {
				this.pins.splice(i,1);
				break;
			}
		}
	};

	this.renderPins=function() {
		this.container.empty();
		this.list.empty();
		this.img=this.container.siblings('img');
		var l=this.pins.length;
		for(var i=0;i<l;i++) {
			this.pins[i].indexNo=i;
			this.pins[i].displayNo=i+1;
			scale=this.img.outerWidth()/this.img.attr('width');
			this.pins[i].tag.get(i,this.img.position().top,this.img.position().left,scale).appendTo(this.container);
			this.pins[i].listItem.get(i).appendTo(this.list);
		}
	};

	this.savePin=function(pin) {
		pin.listItem.disable();

		var data={
			_token:EditorData.token,
			id:pin.id,
			streetsnap_id:EditorData.snap.id,
			item_id:pin.item.item_id,
			brand_id:pin.brand.brand_id,
			link:pin.link,
			top:pin.y,
			left:pin.x
		};

		$.post(EditorData.endpoints.savePin, data, function(response) {
			var msg=null;
			if(typeof response==='object' && 'type' in response && 'data' in response) {
				if(response.type=='success') {
					pin.id=parseInt(response.data,10);
					pin.listItem.enable();
				} else if(response.type='error') {
					switch(response.data) {
						case 'no_pin':
							msg='존재하지 않는 핀 입니다!';
							break;
						case 'permission_error':
							msg='권한이 없습니다!';
							break;
						case 'url_error':
							msg='잘못된 링크 주소 입니다!';
							break;
						case 'invalid_request':
							msg='잘못된 요청 입니다!';
							break;
						case 'db_error':
							msg='데이터베이스 에러가 발생했습니다!';
							break;
						case 'no_brand':
							msg='브랜드가 입력되지 않았습니다!';
							break;
						default:
							msg='알 수 없는 오류가 발생했습니다!';
					}
					pin.listItem.errorMsg(msg);
				} else {
					msg='서버로부터 정상적인 응답을 받지 못했습니다!';
					pin.listItem.errorMsg(msg);
				}
			} else {
				msg='서버로부터 정상적인 응답을 받지 못했습니다!';
				pin.listItem.errorMsg(msg);
			}
		}, 'json');
	};

	this.deletePin=function(pin) {
		pin.listItem.disable();

		if(pin.id) {
			var data={
				_token:EditorData.token,
				id:pin.id,
				streetsnap_id:EditorData.snap.id
			};

			var pinEngine=this;

			$.post(EditorData.endpoints.deletePin, data, function(response) {
				var msg=null;
				if(typeof response==='object' && 'type' in response && 'data' in response) {
					if(response.type=='success') {
						pinEngine.removePin(parseInt(response.data,10));
						pinEngine.renderPins();
					} else if(response.type='error') {
						switch(response.data) {
							case 'permission_error':
								msg='권한이 없습니다!';
								break;
							case 'no_snap':
								msg='존재하지 않는 사진 입니다!';
								break;
							case 'invalid_request':
								msg='잘못된 요청 입니다!';
								break;
							case 'no_pin':
								pinEngine.renderPins();
								break;
							case 'db_error':
								msg='데이터베이스 에러가 발생했습니다!';
								break;
							default:
								msg='알 수 없는 오류가 발생했습니다!';
						}
						pin.listItem.errorMsg(msg);
					} else {
						msg='서버로부터 정상적인 응답을 받지 못했습니다!';
						pin.listItem.errorMsg(msg);
					}
				} else {
					msg='서버로부터 정상적인 응답을 받지 못했습니다!';
					pin.listItem.errorMsg(msg);
				}
			}, 'json');
		} else {
			this.pins.splice(pin.indexNo,1);
			this.renderPins();
		}
	};
};//PinEngine

function PinObject(x, y) {
	var scope=this;

	//Data members
	this.id=null;
	this.indexNo=null;
	this.item={
		item_id:0,
		item_name:'아이템 미지정'
	};
	this.brand={
		brand_id:0,
		brand_name:'브랜드 미지정'
	};
	this.link=null;
	this.x=Math.round(x * 100) / 100;
	this.y=Math.round(y * 100) / 100;
	
	//Tag object
	this.tag={
		jqo:$('<div class="pin"></div>'),
		get:function(indexNo,offsetTop,offsetLeft,scale) {
			this.jqo.attr('data-index', indexNo)
				.css({
					top:(scope.y+offsetTop)*scale,
					left:(scope.x+offsetLeft)*scale
				})
				.text(indexNo+1);
			return this.jqo;
		}
	};

	//List item jquery object
	this.listItem={
		jqo:null,
		item:null,
		brand:null,
		link:null,
		editBtn:null,
		deleteBtn:null,

		init:function() {
			this.jqo=$('<li class="pin"><span class="pin-item"></span> by <span class="pin-brand"></span><a class="pin-link" href="" target="_blank"></a><div class="pin-controls"><a href="" class="pin-edit">수정</a> <a href="" class="pin-delete">삭제</a> <span class="pin-status"></span></div></li>');
			this.item=this.jqo.find('span.pin-item');
			this.brand=this.jqo.find('span.pin-brand');
			this.link=this.jqo.find('a.pin-link');
			this.editBtn=this.jqo.find('a.pin-edit');
			this.deleteBtn=this.jqo.find('a.pin-delete');
			this.status=this.jqo.find('span.pin-status');
		}/*init()*/,

		get:function(indexNo) {
			//Set data
			this.jqo.attr('data-index', indexNo);
			this.item.text(scope.item.item_name);
			if(scope.brand.brand_name=='' || scope.brand.brand_name==null) {
				this.brand.text('브랜드 미지정');
			} else {
				this.brand.text(scope.brand.brand_name);
			}
			if(scope.link==null || scope.link=='') {
				this.link.attr('href', '').text('');
			} else {
				this.link.attr('href', scope.link).text(scope.link);
			}

			//Attach listeners
			//Pin edit
			this.editBtn.on('click', null, {pin:scope}, function(e) {
				e.preventDefault();
				var pinObj=e.data.pin;

				PinEditor.launch(pinObj, function(data) {
					if(data.brand) {
						pinObj.brand=data.brand;
					}
					pinObj.item=data.item;
					pinObj.link=data.link;

					PrimaryEditor.pinEngine.savePin(pinObj);
					PrimaryEditor.pinEngine.renderPins();
				});
			});

			//Pin delete
			this.deleteBtn.on('click', null, {pin:scope}, function(e) {
				e.preventDefault();
				PrimaryEditor.pinEngine.deletePin(e.data.pin);
			});

			return this.jqo;
		}/*get()*/,

		disable:function() {
			this.jqo.addClass('saving');
			this.editBtn.addClass('deactivated');
			this.deleteBtn.addClass('deactivated');
			this.status.removeClass('text-success').removeClass('text-danger').addClass('text-warning').text('저장중...');
		}/*disable()*/,

		enable:function() {
			this.jqo.removeClass('saving');
			this.editBtn.removeClass('deactivated');
			this.deleteBtn.removeClass('deactivated');
			this.status.removeClass('text-warning').removeClass('text-danger').addClass('text-success').text('저장됨');
		}/*enable()*/,

		errorMsg:function(msg) {
			this.jqo.removeClass('saving');
			this.editBtn.removeClass('deactivated');
			this.deleteBtn.removeClass('deactivated');
			this.status.removeClass('text-success').removeClass('text-warning').addClass('text-danger').text(msg);
		}/*errorMsg()*/
	};//listItem

	this.init=function() {
		this.listItem.init();
	};
};//PinObject

var PinEditor={
	jqo:null,
	pin:null,
	callback:null,
	data:{
		brand:{
			brand_id:null,
			brand_name:null
		},
		item:{
			item_id:null,
			item_name:null
		},
		link:null
	},
	init:function(selector) {
		this.jqo=$(selector);
		this.jqo.modal({show:false});

		// constructs the suggestion engine
		var brands = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 15,
			remote:EditorData.endpoints.brandsData+'/%QUERY'
		});
		 
		// kicks off the loading/processing of `local` and `prefetch`
		brands.initialize();
		 
		this.jqo.find('input[name="brand"]').typeahead({
			hint: true,
			highlight: true,
			minLength: 1
		},
		{
			name: 'brands',
			displayKey: 'name_en',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: brands.ttAdapter(),
		}).bind('typeahead:selected', function(e,suggestion,dataset) {
			PinEditor.data.brand.brand_id=suggestion.id;
			PinEditor.data.brand.brand_name=suggestion.name_en;
		});

		this.jqo.on('click','.confirm-btn',{editor:this},function(e) {
			var editor=e.data.editor;

			var itemSelector=editor.jqo.find('select[name="item"]');
			editor.data.item={
				item_id:itemSelector.val(),
				item_name:itemSelector.find('option[value="'+itemSelector.val()+'"]').text()
			};

			editor.data.link=editor.jqo.find('input[name="link"]').val();

			if(typeof editor.callback==='function') {
				editor.callback.apply(null,[editor.data]);
			}
			editor.jqo.modal('hide');
		});

		this.jqo.on('click', '.cancel-btn', {editor:this}, function(e) {
			e.data.editor.jqo.modal('hide');
		});
	}/*init()*/,
	launch:function(pin,callback) {console.log(pin);
		//Setup callback
		this.pin=pin;
		this.callback=callback;

		//Set data
		this.data.brand=this.pin.brand;
		this.data.item=this.pin.item;
		this.data.link=this.pin.link;

		//Set fields
		this.jqo.find('input[name="brand"]').typeahead('val', this.pin.brand.brand_name);
		var itemId=this.pin.item.item_id;
		var itemSelect=this.jqo.find('select[name="item"]');
		itemSelect.find('option:selected').prop('selected', false);
		itemSelect.find('option').filter(function() {
			if($(this).val()==itemId) {
				return $(this);
			}
		}).prop('selected', true);
		this.jqo.find('input[name="link"]').val(this.data.link);

		//Launch modal
		this.jqo.modal('show');
	}/*launch()*/
};//PinEditor{}

var PrimaryEditor={
	jqo:null,
	objects:{
		imageContainer:null,
		imageMsg:null,
		pinContainer:null,
		pinList:null,
		uploadBtn:null,
		toggleBtn:null
	},
	pin_mode:false,
	photo:{
		id:null,
		url:null,
		width:null,
		height:null
	}/*photo{}*/,
	pinEngine:null,
	pinEditor:null,
	init:function() {
		this.jqo=$('#primaryPhotoEditor');
		this.objects.imageContainer=this.jqo.find('figure.primary-photo');
		this.objects.imageMsg=this.jqo.find('.primary-toolbar-msg');
		this.objects.pinContainer=this.objects.imageContainer.find('div.pin-container');
		this.objects.pinList=$('#PinList');
		this.objects.uploadBtn=this.jqo.find('button.upload-btn');
		this.objects.toggleBtn=this.jqo.find('button.toggle-mode-btn');

		//Init Pin Engine
		this.pinEngine=new PinEngine();
		this.pinEngine.init(this.objects.pinContainer, this.objects.pinList);

		//Load existing pins
		this.pinEngine.loadPins(EditorData.pins);

		this.objects.uploadBtn.on('click', null, null, function() {
			UploadModal.launch(null, function(file) {
				if(file instanceof File) {
					PrimaryEditor.upload(file);
				}
			}, null);
		});

		this.objects.toggleBtn.on('click', null, null, function() {
			if(!PrimaryEditor.objects.imageContainer.hasClass('dummy')) {
				if(PrimaryEditor.pin_mode===false) {
					PrimaryEditor.objects.uploadBtn.prop('disabled', true);
					PrimaryEditor.pinMode('on');
					$(this).html('<span class="glyphicon glyphicon-tag"></span> 태깅 완료');
				} else {
					PrimaryEditor.objects.uploadBtn.prop('disabled', false);
					PrimaryEditor.pinMode('off');
					$(this).html('<span class="glyphicon glyphicon-tag"></span> 핀으로 태깅하기');
				}
			}
		});

		this.objects.imageContainer.on('click', 'img', null, function(e) {
			if(PrimaryEditor.pin_mode===true) {
				var img=$(this);
				PrimaryEditor.pinEngine.addPin(e.pageX-img.offset().left,e.pageY-img.offset().top);
			}
		});
	}/*init()*/,
	pinMode:function(action) {
		var container=this.objects.imageContainer;
		if(action=='on') {
			this.pin_mode=true;
			var containerCSS={width:0,height:0};
			containerCSS.width=container.innerWidth();
			containerCSS.height=container.innerHeight();
			containerCSS.display='block';
			container.css(containerCSS);
			container.addClass('pinning');

			container.find('img').draggable({
				start:function(e,ui) {
					container.addClass('moving');
					//PinEngine.hidePins();
				},
				stop:function(e,ui) {
					container.removeClass('moving');

					var fixedPos={};
					if(ui.position.top>0) {
						fixedPos.top=0;
					}
					if(ui.position.left>0) {
						fixedPos.left=0;
					}

					var imgSize={
						width:$(this).outerWidth(),
						height:$(this).outerHeight()
					};

					var containerSize={
						width:container.innerWidth(),
						height:container.innerHeight()
					}

					if(ui.position.left+imgSize.width<containerSize.width) {
						fixedPos.left=containerSize.width-imgSize.width;
					}
					if(ui.position.top+imgSize.height<containerSize.height) {
						fixedPos.top=containerSize.height-imgSize.height;
					}

					ui.helper.animate(fixedPos,200,function() {
						img=container.find('img');
						img.offset=ui.helper.offset();
						img.pos=ui.helper.position();
						PrimaryEditor.pinEngine.renderPins();
					});
				}
			});
		} else {
			this.pin_mode=false;
			container.removeAttr('style').removeClass('pinning');
			container.find('img').removeAttr('style').draggable('destroy');
		}

		this.pinEngine.renderPins();
	},
	upload:function(file) {
		//Disable controls
		this.objects.uploadBtn.prop('disabled', true);
		this.objects.toggleBtn.prop('disabled', true);
		this.setMsg('업로드 중 입니다...', 'info');

		var formData=new FormData();
		formData.append('image', file);
		formData.append('_token', EditorData.token);
		formData.append('streetsnap_id', EditorData.snap.id);
		$.ajax({
			url: EditorData.endpoints.uploadPrimary,
			type: "POST",
			data: formData,
			processData: false,  // tell jQuery not to process the data
			contentType: false,   // tell jQuery not to set contentType
			dataType:'json',
			success:function(response) {
				console.log(response);
				if(typeof response==='object' && 'type' in response && 'data' in response) {
					if(response.type=='success') {
						PrimaryEditor.photo=response.data;
						PrimaryEditor.setPhoto(response.data);
						PrimaryEditor.clearMsg();
						PrimaryEditor.pinEngine.pins=[];
						PrimaryEditor.pinEngine.renderPins();
					} else if(response.type=='error') {
						switch(response.data) {
							case 'file_proc':
							PrimaryEditor.setMsg('파일 처리 실패!', 'danger');
							break;
							case 'no_jpeg':
							PrimaryEditor.setMsg('jpg파일만 올릴 수 있습니다!', 'danger');
							break;
							case'no_file':
							PrimaryEditor.setMsg('정상적인 업로드가 이루어지지 않았습니다!', 'danger');
							break;
							case 'not_found':
							PrimaryEditor.setMsg('존재하지 않는 스타일 아이콘 입니다!', 'danger');
							break;
							case 'not_owner':
							PrimaryEditor.setMsg('편집 권한이 없습니다!', 'danger');
							break;
							default:
							PrimaryEditor.setMsg('알 수 없는 오류가 발생했습니다!', 'danger');
						}
					}
				} else {
					PrimaryEditor.setMsg('정상적인 서버 응답을 받지 못했습니다!', 'danger');
				}
			},
			error:function() {
				PrimaryEditor.setMsg('현재 서버와 통신이 불가능 합니다!', 'danger');
			},
			complete:function() {
				//Enable controls
				PrimaryEditor.objects.uploadBtn.prop('disabled', false);
				PrimaryEditor.objects.toggleBtn.prop('disabled', false);
			}
		});
	}/*upload()*/,
	setPhoto:function(img) {
		var primary=$('<img src="" />').prop('src', img.url).prop('width', img.width).prop('height', img.height);
		this.objects.imageContainer.find('img').remove();
		this.objects.imageContainer.append(primary);
		this.objects.imageContainer.removeClass('dummy');

	}/*setPhoto*/,
	setMsg:function(msg,type) {
		var helperclass=null;
		switch(type) {
			case 'info':
				helperclass='text-info';
				break;
			case 'danger':
				helperclass='text-danger';
				break;
			case 'danger':
				helperclass='text-danger';
				break;
			case 'success':
				helperclass='text-success';
				break;
			default:
				helperclass='text-muted'
		}
		this.objects.imageMsg.html(msg).addClass(helperclass);
	}/*setMsg()*/,
	clearMsg:function() {
		this.objects.imageMsg.html(null).removeClass();
	}/*clearMsg*/
};//PrimaryEditor{}

var AttachmentEditor={
	jqo:null,
	objects:{
		list:null,
		uploadBtn:null,
		toolbarMsg:null
	},
	init:function() {
		this.jqo=$('#attachmentEditor');
		this.objects.list=this.jqo.find('div.attachment-list');
		this.objects.uploadBtn=this.jqo.find('button.upload-btn');
		this.objects.toolbarMsg=this.jqo.find('.attachment-toolbar-msg');

		this.objects.uploadBtn.on('click', null, null, function() {
			UploadModal.launch(null, function(file) {
				if(file instanceof File) {
					AttachmentEditor.upload(file);
				}
			}, null);
		});

		this.objects.list.on('click', '.delete-btn', null, function() {
			AttachmentEditor.delete($(this));
		});
	}/*init()*/,
	upload:function(file) {
		//Disable controls
		this.objects.uploadBtn.prop('disabled', true);
		this.setMsg('업로드 중 입니다...', 'info');

		var formData=new FormData();
		formData.append('image', file);
		formData.append('_token', EditorData.token);
		formData.append('streetsnap_id', EditorData.snap.id);
		$.ajax({
			url: EditorData.endpoints.uploadAttachment,
			type: "POST",
			data: formData,
			processData: false,  // tell jQuery not to process the data
			contentType: false,   // tell jQuery not to set contentType
			dataType:'json',
			success:function(response) {
				if(typeof response==='object' && 'type' in response && 'data' in response) {
					AttachmentEditor.clearMsg();
					if(response.type=='success') {
						AttachmentEditor.addPhoto(response.data);
						//Remove dummy if present
						AttachmentEditor.objects.list.find('figure.dummy').remove();
					} else if(response.type='error') {
						switch(response.data) {
							case 'file_proc':
							AttachmentEditor.setMsg('파일 처리 실패!', 'danger');
							break;
							case 'no_jpeg':
							AttachmentEditor.setMsg('jpg파일만 올릴 수 있습니다!', 'danger');
							break;
							case'no_file':
							AttachmentEditor.setMsg('정상적인 업로드가 이루어지지 않았습니다!', 'danger');
							break;
							case 'not_found':
							AttachmentEditor.setMsg('존재하지 않는 스타일 아이콘 입니다!', 'danger');
							break;
							case 'not_owner':
							AttachmentEditor.setMsg('편집 권한이 없습니다!', 'danger');
							break;
							default:
							AttachmentEditor.setMsg('알 수 없는 오류가 발생했습니다!', 'danger');
						}
					}
				} else {
					AttachmentEditor.setMsg('정상적인 서버 응답을 받지 못했습니다!', 'danger');
				}
			},
			error:function() {
				AttachmentEditor.setMsg('정상적인 서버 응답을 받지 못했습니다!', 'danger');
			},
			complete:function() {
				AttachmentEditor.objects.uploadBtn.prop('disabled', false);
			}
		});
	}/*upload()*/,
	addPhoto:function(img) {
		var attachment=$('<figure class="attachment"></figure>').attr('data-id', img.id);
		$('<button type="button" class="delete-btn btn btn-warning">삭제</button>').attr('data-id', img.id).appendTo(attachment);
		$('<img src="" />').attr('src', img.url).attr('width', img.width).attr('height', img.height).appendTo(attachment);
		this.objects.list.append(attachment);
	},
	delete:function(btn) {console.log(btn);
		var data={
			_token:EditorData.token,
			streetsnap_id:EditorData.snap.id,
			attachment_id:btn.attr('data-id')
		};

		//Disable delete btn
		btn.prop('disabled', true);

		$.post(EditorData.endpoints.deleteAttachment, data, function(response) {
			var errorMsg=null;
			if(typeof response==='object' && 'type' in response && 'data' in response) {
				if(response.type=='success') {
					AttachmentEditor.removePhoto(response.data);
				} else if(response.type='error') {
					switch(response.data) {
						case 'file_proc':
						errorMsg='파일 처리 실패!';
						break;
						case'no_file':
						errorMsg='정상적인 업로드가 이루어지지 않았습니다!';
						break;
						case 'not_found':
						errorMsg='존재하지 않는 스타일 아이콘 입니다!';
						break;
						case 'not_owner':
						errorMsg='편집 권한이 없습니다!';
						break;
						default:
						errorMsg='알 수 없는 오류가 발생했습니다!';
					}
				}
			} else {
				errorMsg='정상적인 서버 응답을 받지 못했습니다!';
			}

			if(errorMsg) {
				AlertModal.launch(errorMsg, function(btn) {
					btn.prop('disabled', false);
				}, [btn]);
			}
		}, 'json');
	},
	removePhoto:function(id) {
		this.objects.list.find('figure[data-id="'+id+'"]').remove();
		if(this.objects.list.find('figure').length===0) {
			$('<figure class="dummy"></figure>').appendTo(this.objects.list);
		}
	},
	setMsg:function(msg,type) {
		var helperclass=null;
		switch(type) {
			case 'info':
				helperclass='text-info';
				break;
			case 'danger':
				helperclass='text-danger';
				break;
			case 'danger':
				helperclass='text-danger';
				break;
			case 'success':
				helperclass='text-success';
				break;
			default:
				helperclass='text-muted'
		}
		this.objects.toolbarMsg.html(msg).addClass(helperclass);
	},
	clearMsg:function() {
		this.objects.toolbarMsg.html(null).removeClass();
	}
};//AttachmentEditor{}

var MetaEditor={
	objects:{
		metaForm:null,
		deleteForm:null,
		metaValue:null,
		metaInput:null,
		deleteBtn:null,
		publishBtn:null
	},
	init:function() {
		var editor=$('.meta-section');
		this.objects.metaForm=editor.find('#streetSnapForm');
		this.objects.deleteForm=editor.find('#snapDeleteForm');
		this.objects.metaType=this.objects.metaForm.find('input[name="meta_type"]');
		this.objects.metaValue=this.objects.metaForm.find('input[name="meta_id"]');
		this.objects.metaInput=this.objects.metaForm.find('input[name="meta_category"]');
		this.objects.deleteBtn=$('.post-controls').find('.delete-btn');
		this.objects.publishBtn=$('.post-controls').find('.publish-btn');

		//Meta autocomplete
		var campus = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 15,
			remote:{
				url:EditorData.endpoints.metaData+'/%QUERY',
				filter:function(response) {
					return response.campus_meta.data;
				}
			}
		});
		campus.initialize();

		var street = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 15,
			remote:{
				url:EditorData.endpoints.metaData+'/%QUERY',
				filter:function(response) {
					return response.street_meta.data;
				}
			}
		});
		street.initialize();

		var festival = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 15,
			remote:{
				url:EditorData.endpoints.metaData+'/%QUERY',
				filter:function(response) {
					return response.festival_meta.data;
				}
			}
		});
		festival.initialize();

		var club = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 15,
			remote:{
				url:EditorData.endpoints.metaData+'/%QUERY',
				filter:function(response) {
					return response.club_meta.data;
				}
			}
		});
		club.initialize();

		var fashionweek = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 15,
			remote:{
				url:EditorData.endpoints.metaData+'/%QUERY',
				filter:function(response) {
					return response.fashionweek_meta.data;
				}
			}
		});
		fashionweek.initialize();

		var blog = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 15,
			remote:{
				url:EditorData.endpoints.metaData+'/%QUERY',
				filter:function(response) {
					return response.blog_meta.data;
				}
			}
		});
		blog.initialize();

		var magazine = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit: 15,
			remote:{
				url:EditorData.endpoints.metaData+'/%QUERY',
				filter:function(response) {
					return response.magazine_meta.data;
				}
			}
		});
		magazine.initialize();
		 
		this.objects.metaInput.typeahead({
			hint: true,
			highlight: true,
			minLength: 1
		},
		{
			name: 'CampusMeta',
			displayKey: 'name',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: campus.ttAdapter(),
			templates:{
				header:'<h5 class="tt-header">Campus</h5>'
			}
		},
		{
			name: 'StreetMeta',
			displayKey: 'name',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: street.ttAdapter(),
			templates:{
				header:'<h5 class="tt-header">Street</h5>'
			}
		},
		{
			name: 'FestivalMeta',
			displayKey: 'name',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: festival.ttAdapter(),
			templates:{
				header:'<h5 class="tt-header">Festival</h5>'
			}
		},
		{
			name: 'ClubMeta',
			displayKey: 'name',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: club.ttAdapter(),
			templates:{
				header:'<h5 class="tt-header">Club</h5>'
			}
		},
		{
			name: 'FashionWeekMeta',
			displayKey: 'name',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: fashionweek.ttAdapter(),
			templates:{
				header:'<h5 class="tt-header">Fashion Week</h5>'
			}
		},
		{
			name: 'BlogMeta',
			displayKey: 'name',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: blog.ttAdapter(),
			templates:{
				header:'<h5 class="tt-header">Blog</h5>'
			}
		},
		{
			name: 'MagazineMeta',
			displayKey: 'name',
			// `ttAdapter` wraps the suggestion engine in an adapter that
			// is compatible with the typeahead jQuery plugin
			source: magazine.ttAdapter(),
			templates:{
				header:'<h5 class="tt-header">Magazine</h5>'
			}
		}).bind('typeahead:selected', function(e,suggestion,dataset) {
			MetaEditor.objects.metaValue.val(suggestion.id);
			MetaEditor.objects.metaType.val(dataset);
		});

		//Delete btn
		this.objects.deleteBtn.on('click', null, {deleteForm:this.objects.deleteForm}, function(e) {
			var deleteForm=e.data.deleteForm;
			ConfirmModal.launch('작성중인 내용을 모두 삭제합니다.', function() {
				deleteForm.submit();
			}, null);
		});

		//Submit btn
		this.objects.publishBtn.on('click', null, {metaForm:this.objects.metaForm}, function(e) {
			var metaForm=e.data.metaForm;
			metaForm.submit();
		});
	}
};//MetaEditor{}

$(document).ready(function() {
	PrimaryEditor.init();
	AttachmentEditor.init();
	PinEditor.init('#PinEditModal');
	MetaEditor.init();
});//document.ready()