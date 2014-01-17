/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

spnoConflict(function($){
	
	$('.sp-menu.level-0').addClass('nav');

	$menu_items = $('ul.level-0 > li');
	
	$('ul.level-0 > li a.menu-item').click(function(event) {
		
		var target = $(this).prop('hash');
		if(target) {
			event.preventDefault();

			$menu_items.removeClass('active');
			$(this).parent().addClass('active');

			$('html, body').animate({
				scrollTop: $(target).offset().top - $('#sp-header-wrapper').height()
			}, 500);
		}
	});

	//scrollspy
	$('[data-spy="scroll"]').each(function () {
		var $spy = $(this).scrollspy('refresh')
	});

});