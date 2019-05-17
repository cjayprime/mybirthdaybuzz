$(document).ready(function(e) {
	GIFTS = {};
	BUZZ = {};	
	ENTER_TO_SEND = true;
	window.FILE = Array();
	AJAX = function(url,data,success,error,xhrHandler){
		$.ajax({
			url:url,
			data:data,
			method:'POST',
			dataType:"json",
			success:function(res,status,xhr){
				success(res,status,xhr);
			},
			error:function(res,status,xhr){
				error(res,status,xhr);
			},
			xhr:function(){
				
				var xhr = $.ajaxSettings.xhr();
				if(xhr.upload){
					xhr.upload.addEventListener('progress',function(ev){
						var position = ev.loaded || ev.position;
						var total = ev.total;
						if(ev.lengthComputable){
							var percent = Math.ceil(position/total * 100);
							if(typeof xhrHandler == 'function')
							xhrHandler(percent);
						}
					});
				}
				return xhr;
				
			},
			contentType: false,
			processData: false
		});
	};
	SEND_COMMENT = function(){
		var comment = $('#addcomment').val();
		var files = window.FILE;
		if(!/^(\s)*$/.test(comment)){
			var formdata = new FormData();
			formdata.append('board_id',$('#header-image').data('identification'));
			formdata.append('comment',comment);
			for(var i = 0; i < $('.caption').length; i++){
				if($('.caption').eq(i).parent('.selected-image').data('removed') == false)
				formdata.append('captions[]',$('.caption').eq(i).val());
			}
			for(var i = 0; i < files.length; i++){
				if(files[i] == null)continue;
				formdata.append('files[]',files[i]);
			}
			
			AJAX('api/comment.php',formdata
			,function(res){
				console.log(res)
				$('#upload-progress').fadeOut(1000,function(){
					$(this).css({display:'none'});
					$('#selected-images').hide();
				});
			},function(res){
				console.log(res.responseText)
				UPLOAD_NOTICE('AN UNKNOWN ERROR OCCURRED');
			},function(percent){
				$('#upload-progress').css({display:'flex'}).text(percent+'%');
			});
		}else{
			NOTICE('YOU NEED TO ENTER A COMMENT.');
		}
		
	};
	NOTICE = function(txt){
		alert(txt);
	};
	UPLOAD_NOTICE = function(txt,type){
		if(!type)type = 'red';
		$('#upload-error').css({background:type}).append(txt+'<br>').fadeOut(10000);
	};
	FILESELECT = function(e){
		$('#selected-images').css({display:'flex'});
		//SELECT IMAGE
		var file = e.target.files  || e.dataTransfer.files;
		var error_size = 0;
		var error_type = 0;
		for(var i = 0; i < file.length; i++){
			if((file[i].size/1024) <= 128 
			&& (file[i].type == 'image/png' || file[i].type == 'image/jpg' || file[i].type == 'image/jpeg' || file[i].type == 'image/gif')){
				window.FILE.push(file[i]);
				var f = new FileReader();
				f.readAsDataURL(file[i]);
				f.onload = function(e){
					var src = this.result;
					$('#selected-images').append(
						'<div class="selected-image" data-removed="false">'+
							'<img src="images/actions/close.png" id="comment-file" />'+
							'<input type="text" class="caption" placeholder="Enter a caption of less than 50 characters" maxlength="49"/>'+
						'</div>'
					);
					$('.selected-image:last').css({'background-image':'url("'+src+'")'});
					//REMOVE AN IMAGE FROM THE LIST OF FILES TO UPLOAD
					$('.selected-image:last').children('img').click(function(){
						$(this).parent('.selected-image').fadeOut(function(){
							var i = $(this).data('removed',true).index('.selected-image');
							window.FILE.splice(i,1,null);
							console.log(FILE,i)
							$(this).hide();
						});
					});
				};
			}
			if((file[i].size/1024) > 128)
			error_size++;
			if(!(file[i].type == 'image/png' || file[i].type == 'image/jpg' || file[i].type == 'image/jpeg' || file[i].type == 'image/gif'))
			error_type++;
		}
		
		$('#upload-error').text('');
		if(error_size > 0){
			var size_text = (error_size == 1) ? '1 IMAGE WAS REMOVED FROM YOUR SELECTION BECAUSE IT\'S SIZE MUST BE EQUAL TO OR LESS THAN 128KB' : error_size+' IMAGES WERE REMOVED FROM YOUR SELECTION BECAUSE THEIR INDIVIDUAL SIZES MUST BE EQUAL TO OR LESS THAN 128KB';
			UPLOAD_NOTICE(size_text);
		}
		if(error_type > 0){
			var type_text = (error_type == 1) ? '1 IMAGE WAS REMOVED FROM YOUR SELECTION BECAUSE THEIR FILE TYPE MUST BE OF A PNG OR JP[E]G IMAGE' : error_type+' IMAGES WERE REMOVED FROM YOUR SELECTION BECAUSE THEIR FILE TYPES MUST BE OF A PNG OR JP[E]G IMAGE';
			UPLOAD_NOTICE(type_text);
		}
	};
	//UPDATE THE COMMENTS IN REAL TIME
	UPDATE = function(){
		
		var data = {update:'comments',identification:$('#header-image').data('identification'),start:$('.comments:last').data('commentid') || 0};
		var formdata = new FormData();
		for(var key in data)formdata.append(key,data[key]);
		AJAX('api/update.php',formdata,
		function(res){
			//console.log(res);
			if(res.status == true){
				for(var j = 0; j < res.message.length; j++){
					var comment = res.message[j]['comment'];
					var comment_id = res.message[j]['comment_id'];
					var firstname = res.message[j]['firstname'];
					var lastname = res.message[j]['lastname'];
					var profileimage = res.message[j]['profileimage'];
					var profileimage = res.message[j]['profileimage'];
					var commentimages = res.message[j]['commentimages'];
					var images = '';
					for(var i = 0; i < commentimages.length; i++){
						images += '<div class="comment-image" style="background-image:url(\''+commentimages[i]['url']+'\')">'+
									'<div class="comment-caption">'+commentimages[i]['caption']+'</div>'+
								'</div>';
					}
					$('#comment-box').before(
						'<div class="comments" data-commentid="'+comment_id+'">'+
							'<div class="comment-profile" style="background-image:url(\''+profileimage+'\')"></div>'+
							'<div class="comment-fullname"><b>'+firstname+'</b> '+lastname+'</div>'+
							'<div class="comment-text">'+
								'<div>'+
									comment+
								'</div>'+
							'</div>'+
							'<div class="comment-images">'+
								images+
							'</div>'+
						'</div>'
					);
				}
			}
			setTimeout(UPDATE,3000);
			
		},
		function(res){
			//console.log(res.responseText);
			setTimeout(UPDATE,3000);
		},
		function(){
		});
	};
	
	
	
	
	
	//HEADER AND PROFILE IMAGE UPLOAD
	$('#header-image,#profile-image').children('img').click(function(){
		$(this).parent('div').children('input').click();
	});
	$('#header-image,#profile-image').children('input').change(function(e){
		var which = $(this).parent('div').is('#header-image') ? 'header' : 'profile';
		var that = $('#'+which+'-upload-progress');
		var parent = $('#'+which+'-image');
		var file = e.target.files  || e.dataTransfer.files;
		var formdata = new FormData();
		formdata.append('board_id',$('#header-image').data('identification'));
		formdata.append(which+'-image',file[0])
		AJAX('api/profile.php',formdata
		,function(res){
			var f = new FileReader();
			f.readAsDataURL(file[0]);
			f.onload = function(e){
				parent.css({backgroundImage:'url("'+this.result+'")'});
			};
			that
			.text(res.message)
			.fadeOut(2000,function(){
				$(this).hide();
			});
		},function(res){
			that.css({display:'none'});
		},function(percent){
			that.css({display:'flex'}).text(percent+'%');
		});
    });
	
	
	
	
	
	//WHEN A CAPTION IS HOVERED OVER, ANIMATE IT OUT
	$('.comment-image').hover(
	function(){
		$(this).children('.comment-caption').animate({opacity:0.5});
	},function(){
		$(this).children('.comment-caption').animate({opacity:0});
	});
	
	
	
	
	
	
	/*SEND COMMENTS AND IMAGES*/
	//SEND COMMENTS WHEN ENTER IS PRESSED ON THE TEXTAREA
	$('#addcomment').keyup(function(e) {
        if(e.which == 13 && ENTER_TO_SEND){
			SEND_COMMENT();
		}
    });
	//SEND COMMENTS AND FILES WHEN SEND IS CLICKED
	$('#send-button').click(function(){
		if(!ENTER_TO_SEND)
		SEND_COMMENT();
	});
	$('#entertosendcontrol').change(function(e){
		if($(this).prop('checked') == true){
			ENTER_TO_SEND = true;
			$('#send-button').css({cursor:'inherit',opacity:0.2})
		}else{
			ENTER_TO_SEND = false;
			$('#send-button').css({cursor:'pointer',opacity:1});
		}
    }).click();
	//CLOSE THE SELECTED UPLOADABLE IMAGES AND REMOVE ALL
	$('#selected-images').children('img').click(function(){
		$('#selected-images').hide();
		window.FILE = Array();
	});
	//FILE UPLOAD
	$('#comment-file').click(function(){
		$('#comment-file-upload').click();
	});
	$('#comment-file-upload').change(FILESELECT);
	
	
	
	
	
	
	//AUTOMATICALLY ADJUST TEXTAREA WHEN TYPED
	//Courtsey: https://codepen.io/vsync/pen/czgrf
	$('#addcomment')[0].addEventListener('keydown',function autosize(){
		var el = this;
		setTimeout(function(){
			el.style.cssText = 'height:auto; padding:0';
			// for box-sizing other than "content-box" use:
			// el.style.cssText = '-moz-box-sizing:content-box';
			el.style.cssText = 'height:' + el.scrollHeight + 'px';
		},0);
	});
	
	
	
	
	
	
	//UPDATE COMMENTS
	setTimeout(UPDATE,1000);
	
});