$(document).ready(function(e){
	
	$('#overlay,.close-modal').click(function(e) {
        $('.close-modal').parent('div').add('#overlay').fadeOut(function(){
			$(this).hide();
		});
    });
	
	
	//CREATE BIRTHDAY, GIVE GIFT, CREATE BIRTHDAY BUZZ, GET GIFT, CALENDAR
	$('#create-birthday,#give-gift,#create-birthday-buzz,#get-gift,#calendar').click(function(){
		
		$('#overlay,#'+$(this).attr('id')+'-modal').css({display:'flex'});
		
	});
	
	
	//CREATE BIRTHDAY
	$('#create-birthday-now').click(function(e){
		var months = {JAN:1,FEB:2,MAR:3,APR:4,MAY:5,JUN:6,JUL:7,AUG:8,SEP:9,OCT:10,NOV:11,DEC:12};
		var day = $('#create-birthday-day').val();
		var month = $('#create-birthday-month').val();
		var year = $('#create-birthday-year').val();
		if(day == 'DAY'){
			$('.modal-notice').css({background:'red',display:'flex'}).html('SELECT A DAY').fadeOut(1000);
			return;
		}
		if(month == 'MONTH'){
			$('.modal-notice').css({background:'red',display:'flex'}).html('SELECT A MONTH').fadeOut(1000);
			return;
		}
		if(year == 'YEAR'){
			$('.modal-notice').css({background:'red',display:'flex'}).html('SELECT A YEAR').fadeOut(1000);
			return;
		}
		
		var data = {day:day,month:months[month],year:year};
		
		var formdata = new FormData();
		for(var key in data)formdata.append(key,data[key]);
		AJAX('api/actions/createbirthday.php',formdata
		,function(res){
			var bg = res.status ? 'green' : 'red';
			$('#create-birthday-modal .modal-notice').html(res.message).css({display:'flex',background:bg}).fadeOut(5000,function(){
				$(this).hide();
			});
		},function(res){
			$('#create-birthday-modal .modal-notice').html('AN UNKNOWN ERROR OCCURRED').css({display:'flex',background:'red'}).fadeOut(5000,function(){
				$(this).hide();
			});
		});
    });
	
	
	//GIVE GIFT
	$('.send-gift-list').click(function(e){
		var self = $(this);
		
		var data = {username:$('#send-gift-input').val(),type:self.parent('div').data('type')};
		
		var formdata = new FormData();
		for(var key in data)formdata.append(key,data[key]);
		AJAX('api/actions/givegift.php',formdata
		,function(res){
			var bg = res.status ? 'green' : 'red';
			$('#give-gift-modal .modal-notice').show().html(res.message).css({display:'flex',background:bg}).fadeOut(5000,function(){
				$(this).hide();
			});
		},function(res){
			$('#give-gift-modal .modal-notice').show().html('AN UNKNOWN ERROR OCCURRED').css({display:'flex',background:'red'}).fadeOut(5000,function(){
				$(this).hide();
			});
		});
		
	});
	
	
	//CREATE BIRTHDAY BUZZ
	$('#create-birthday-buzz-now').click(function(e){
		var data = {username:$('#create-birthday-buzz-input').val()};
		
		var formdata = new FormData();
		for(var key in data)formdata.append(key,data[key]);
		AJAX('api/actions/createbirthdaybuzz.php',formdata
		,function(res){
			var bg = res.status ? 'green' : 'red';
			$('#create-birthday-buzz-now .modal-notice').show().html(res.message).css({display:'flex',background:bg}).fadeOut(5000,function(){
				$(this).hide();
			});
		},function(res){
			$('#create-birthday-buzz-now .modal-notice').show().html('AN UNKNOWN ERROR OCCURRED').css({display:'flex',background:'red'}).fadeOut(5000,function(){
				$(this).hide();
			});
		});
		
	});
});