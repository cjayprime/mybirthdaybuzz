$(document).ready(function(e){

	//SLIDESHOW STARTS 10 SECONDS AFTER PAGE LOAD
	W = $(window).width();
	L_ID = 0;
	R_ID = 0;
	I = 0;
	S_ID = 0;
	FILE = {'header-image':'','profile-image':''};
	FILESELECT = function(e){
		FILEDRAG(e);
		var that = $(this);
		var t = that.is($('#header-upload')) ? 'header-image' : 'profile-image';
		//SELECT IMAGE
		var file = e.target.files  || e.dataTransfer.files;
		
		//Check size and type
		if((file[0].size/1024) <= 128 
			&& (file[0].type == 'image/png' || file[0].type == 'image/jpg' || file[0].type == 'image/jpeg')){
			window.FILE[t] = file[0];
			var f = new FileReader();
			f.readAsDataURL(FILE[t]);
			f.onload = function(e){
				var src = this.result;
				$('.'+t).css({'background-image':'url("'+src+'")'});
			};
		}else if((file[0].size/1024) > 128){
			var elem = $('#signup-modal');
			ERROR(elem,'THE FILE SIZE MUST BE EQUAL TO OR LESS THAN 128KB');
		}else{
			var elem = $('#signup-modal');
			ERROR(elem,'THE FILE MUST BE A PNG OR JP[E]G IMAGE');
		}
	};
	FILEDRAG = function(e){
		e.stopPropagation();
		e.preventDefault();
	};
	AJAX = function(url,data,success,error){
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
			contentType: false,
			processData: false
		});
	};
	ERROR = function(elem,txt){
		elem.children('.response').show().css({background:'red'}).append('<div>'+txt+'.</div>').fadeOut(10000);
	};
	SUCCESS = function(elem,txt){
		elem.children('.response').show().css({background:'green'}).append('<div>'+txt+'.</div>').fadeOut(10000);
	};
	AUTOMATE_SLIDESHOW = function(){
		S_ID = setInterval(function(){
			$('.carousel').eq(I).click();
			I++;
			if(I == $('.carousel').length)I = 0;
		},4000);
	};
	END_AUTOMATE_SLIDESHOW = function(){
		clearInterval(S_ID);
	};
	EMAIL_REGEX = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	WHITESPACE_REGEX = /^(\s)+$/;
	TEXTANDNUMBERONLY_REGEX = /^([A-Za-z])+$/;
	TEXTONLY_REGEX = /^([A-Za-z])+$/;
	NUMBERONLY_REGEX = /^([0-9])+$/;
	
});