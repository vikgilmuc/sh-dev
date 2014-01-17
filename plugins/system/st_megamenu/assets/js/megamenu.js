(function( $ ){
	STMegaMenu = {}
	
	STMegaMenu.init = function () {
		$('.st-mega-menu').each(function()
		{
			$(this).find('li').each(function(){
				var li  = $(this);
				
				li.hover(function() {
					var wW = $(window).width();
					if(wW < 768) return; 
					var row = $(this).find("> .st-mega-menu-row");
						
					row.each(function()
					{
						var wSub = $(this).width();
						var leftLi = li.position();
						
						if (leftLi.left + wSub > wW) {
							$(this).css('left', '-' + (wSub - li.width()) + 'px');
						} else {
							$(this).css('left', '');
						} 
					});
				},
				function (){
					var wW = $(window).width();
					var row = $(this).find("> .st-mega-menu-row");
					if(wW < 768) return;
					row.each(function()
					{
						var wSub = $(this).width();
						var leftLi = li.position();
						
						if (leftLi.left + wSub > wW) {
							$(this).css('left', '-9999px');
						} 
					});
				});
			});	
		});
	}
	
	$(document).ready(function(){
		STMegaMenu.init();
	});
})( jQuery );