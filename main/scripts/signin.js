$(document).ready(function(e){
	
	SIGNIN_MODAL = function(){
		$('#signin-modal,.cover').fadeIn(500);
	};
	
	$('#signin').click(function(){
		
		SIGNIN_MODAL();
		
	});
	
	$('#submit-signin').click(function(){
		
		var data = {};
		var elem = $('#signin-modal');
		
		elem.children('.response').text('').hide();
		
		data.email = elem.find('.email').val();
		data.password = elem.find('.password').val();
		
		var formdata = new FormData();
		for(var key in data)formdata.append(key,data[key]);
		
		AJAX('api/signin.php',formdata
		,function(res){
			if(res.status == false){
				var elem = $('#signin-modal');
				ERROR(elem,res.message);
			}else{
				var elem = $('#signin-modal');
				SUCCESS(elem,res.message);
				setTimeout(function(){
					window.location = 'board.php';
				},1500);
			}
		}
		,function(res){
			console.log(res.responseText)
			var elem = $('#signin-modal');
			ERROR(elem,'AN UNKNOWN ERROR OCCURRED.');
		});
	});
	
});