$(document).ready(function(e) {
    
	$('#menu').click(function(e){
		
		var self = $(this);
		
		self.css({backgroundSize:0})
		.animate({backgroundSize:180},{step:function(now){
			self.css({transform:'rotate('+now+'deg)'});
		},complete:function(now){
			self.css({backgroundSize:0,transform:'rotate(0deg)'});
		}});
		
		if(self.data('state') == 'close'){
			self.data('state','open');
			self.children('img')[0].src = 'images/actions/closemenu.png';
			$('.menuitem').eq(0).css({marginTop:-50});
			$('.menuitem').eq(0).animate({marginTop:0},{queue:false});
			$('.menuitem').css({opacity:0});
			$('.menuitem').animate({opacity:1},{queue:false});
		}else if(self.data('state') == 'open'){
			self.data('state','close');
			self.children('img')[0].src = 'images/actions/menu.png';
			$('.menuitem').eq(0).css({marginTop:0});
			$('.menuitem').eq(0).animate({marginTop:-50},{queue:false});
			$('.menuitem').css({opacity:1});
			$('.menuitem').animate({opacity:0},{queue:false});
		}
		
    });

});