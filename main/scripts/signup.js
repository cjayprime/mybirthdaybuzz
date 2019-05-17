$(document).ready(function(e){
	
	SIGNUP_MODAL = function(){
		$('#signup-modal,.cover').fadeIn(500);
	};
	
	$('#signup').click(function(){
		
		SIGNUP_MODAL();
		
	});
	
	var hnp = $('#header-upload,#profile-upload');
	hnp.change(FILESELECT);
	hnp[0].addEventListener("drop",FILESELECT,false);
	hnp[0].addEventListener("dragover",FILEDRAG,false);
	hnp[0].addEventListener("dragleave",FILEDRAG,false);
	
	$('#submit-signup').click(function(){
		
		var data = {};
		var elem = $('#signup-modal');
		
		elem.children('.response').text('').hide();
		
		var username = elem.find('.username').val();
		var firstname = elem.find('.firstname').val();
		var lastname = elem.find('.lastname').val();
		var mobile = elem.find('.mobile').val();
		var email = elem.find('.email').val();
		var password = elem.find('.password').val();
		var repassword = elem.find('.repeat-password').val();
		
		//USERNAME
		if(WHITESPACE_REGEX.test(username) || !TEXTANDNUMBERONLY_REGEX.test(username)){
			ERROR(elem,'Enter your username');
			return;
		}else data.username = username;
		
		//FIRSTNAME
		if(WHITESPACE_REGEX.test(firstname) || !TEXTONLY_REGEX.test(firstname)){
			ERROR(elem,'Enter your firstname');
			return;
		}else data.firstname = firstname;
		
		//LASTNAME
		if(WHITESPACE_REGEX.test(lastname) || !TEXTONLY_REGEX.test(lastname)){
			ERROR(elem,'Enter your middlename');
			return;
		}else data.lastname = lastname;
		
		//MOBILE
		if(WHITESPACE_REGEX.test(mobile)){
			ERROR(elem,'Enter your mobile');
			return;
		}if(!NUMBERONLY_REGEX.test(mobile)){
			ERROR(elem,'Enter only numbers as your mobile number');
			return;
		}else data.mobile = mobile;
		
		//EMAIL
		if(WHITESPACE_REGEX.test(email)){
			ERROR(elem,'Enter your email address');
			return;
		}if(!EMAIL_REGEX.test(email)){
			ERROR(elem,'Enter a valid email address');
			return;
		}else data.email = email;
		
		//PASSWORD
		if(WHITESPACE_REGEX.test(password)){
			ERROR(elem,'Enter your password');
			return;
		}if(password < 5){
			ERROR(elem,'Enter 5 or more characters as your password');
			return;
		}if(password !== repassword){
			ERROR(elem,'Repeat your password');
			return;
		}else data.password = password;
		
		//HEADER IMAGE IF SET
		var formdata = new FormData();
		for(var key in data)formdata.append(key,data[key]);
		formdata.append('header-image',window.FILE['header-image']);
		
		//PROFILE IMAGE IF SET
		formdata.append('profile-image',window.FILE['profile-image']);
		
		AJAX('api/signup.php',formdata
		,function(res){
			if(res.status == false){
				var elem = $('#signup-modal');
				ERROR(elem,res.message);
			}else{
				var elem = $('#signup-modal');
				SUCCESS(elem,res.message);
				$('.close').click();
				$('#signin').click();
			}
		}
		,function(res){
			var elem = $('#signup-modal');
			ERROR(elem,'AN UNKNOWN ERROR OCCURRED.');
		});
		
	});
		
	$('.header-image').click(function(){
		$('#header-upload').click();
	});
		
	$('.profile-image').click(function(){
		$('#profile-upload').click();
	});
	
	
	
	//$('#signup').click();
});