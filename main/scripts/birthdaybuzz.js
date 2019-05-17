$(document).ready(function(e){
	
	AUTOMATE_SLIDESHOW();
	//SLIDE SHOW
	$('.carousel').click(function(e){
		END_AUTOMATE_SLIDESHOW();
		setTimeout(function(){
			//RESTART SLIDESHOW IN 100 MILLISECONDS
			AUTOMATE_SLIDESHOW();
		},100);
		/*PLACE class="view" on the .carousel AND class="active" ON THE ELEMENT TO SLIDE*/
		var self = $(this);
		var index = self.index('.carousel');
		var view = $('.view');
		var active = $('.active');
		var duration = 1000;
		
		$('.carousel').css({background:'transparent'});
		self.css({background:'#FFFFFF'});			
		$('.image').css({zIndex:'400'});
		$('.active').css({zIndex:'500'});
		$('.image').eq(index).css({zIndex:'500'});
		
		if(view.prevAll('div').is(self)){
			//console.log('LEFT',$('.image').eq(index),index)
			active.animate({left:W},duration);
			$('.image').eq(index)
			.css({left:-W})
			.animate({left:0},duration,function(){
				//RUNS AFTER .active HAS CHANGED TO A NEW ELEMENT			
				$('.image').css({zIndex:'400'});
				$('.active').css({zIndex:'500'});
			});
		}if(view.nextAll('div').is(self)){
			//console.log('RIGHT',active,index)
			active.animate({left:-W},duration);
			$('.image').eq(index)
			.css({left:W})
			.animate({left:0},duration,function(){
				//RUNS AFTER .active HAS CHANGED TO A NEW ELEMENT			
				$('.image').css({zIndex:'400'});
				$('.active').css({zIndex:'500'});
			});
		}
		
		$('.image').removeClass('active');
		$('.carousel').removeClass('view');
		$('.image').eq(index).addClass('active');
		self.addClass('view');
		
    }).eq(0).click();
	
	//SCROLL LEFT
	$('#left-scroll').click(function(){
		$('#scrollable').stop(false,true,true);
		$('#scrollable').animate({scrollLeft:'-=50'});
	}).mousedown(function(){
		L_ID = setInterval(function(){
			$('#scrollable').stop(false,true,true);
			$('#scrollable').animate({scrollLeft:'-=50'});
		},300);
	}).mouseup(function(e) {
		$('#scrollable').stop(false,true,true);
        clearInterval(L_ID);
    });
	
	//SCROLL RIGHT
	$('#right-scroll').click(function(){
		$('#scrollable').stop(false,true,true);
		$('#scrollable').animate({scrollLeft:'+=50'});
	}).mousedown(function(){
		R_ID = setInterval(function(){
			$('#scrollable').stop(false,true,true);
			$('#scrollable').animate({scrollLeft:'+=50'});
		},300);
	}).mouseup(function(e){
		$('#scrollable').stop(false,true,true);
        clearInterval(R_ID);
    });
	
	//CLOSE MODALS
	$('.close').click(function(){
		$('.cover,#signup-modal,#signin-modal').fadeOut(500);
	});
	
	//CREATE BIRTHDAY | GIVE GIFT | CREATE BIRTHDAY BUZZ | GET GFIT | CALENDAR
	var elem = $('#create-birthday,#give-gift,#create-birthday-buzz,#get-gift,#calendar');
	elem/*.add(elem.parent('div')).add(elem.parent('div').prev('span'))*/.click(function(){
		var self = $(this);
		AJAX('api/verify.php',{}
		,function(res){
			if(res.status == true){
				window.location = 'board.php?ref='+self.attr('id');
			}else{
				$('html,body').animate({scrollTop:0});
				$('#signin').click();
			}
		},function(res){
			$('html,body').animate({scrollTop:0});
			$('#signin').click();
		});
	});
	
});