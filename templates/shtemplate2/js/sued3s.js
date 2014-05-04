

jQuery(document).ready(function(){


jQuery('#mainmenu').onePageNav({
    currentClass: 'active',
    changeHash: false,
    scrollSpeed: 750,
    scrollOffset: 150,
    scrollThreshold: 0.5,
    filter: '',
    easing: 'swing',
    begin: function() {
        //I get fired when the animation is starting
    },
    end: function() {
        //I get fired when the animation is ending
    },
    scrollChange: function($currentListItem) {
        //I get fired when you enter a section and I pass the list item of the section
    }
})

jQuery('[title="impressum"]').unbind('click');
	
jQuery("a.plan").fancybox();
	

jQuery('.plan').attr("rel","fancied").fancybox({
  afterShow: function(){
    var win=null;
    var content = jQuery('.fancybox-inner');
    jQuery('.fancybox-wrap')
    // append print button
    .append('<div id="fancy_print"></div>')
    // use .on() in its delegated form to bind a click to the print button event for future elements
    .on("click", "#fancy_print", function(){
      win = window.open("width=200,height=200");
      self.focus();
      win.document.open();
      win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
      win.document.write('body, td { font-family: Verdana; font-size: 10pt;}');
      win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');
      win.document.write(content.html());
      win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
      win.document.close();
      win.print();
      win.close();
    }); // on
  } //  afterShow
 }); // fancybox
	
	
});

/**

jQuery(document).ready(function() {    

	var theWindow        = jQuery('#sh-manifest');
	 $bgimage              = jQuery("#bg");
		
	   
	var wwindow = jQuery(window).width();
	
	var hshmanifest = jQuery('#sh-manifest').height();
		
	function resizeBg() {
		$bgimage
		    	
				jQuery('#bg').css("height",(hshmanifest+100)+"px");
		    	//.addClass('bgheight');	

					
	}
	                   			
	theWindow.resize(resizeBg).trigger("resize");
//console.log(hshmanifest);
var wbg = jQuery("#bg").width();

//console.log(wbg);
//console.log(wwindow);
if ((-(wbg-wwindow)/2)>=0) {
		jQuery('#bg').css("left","-750px");
	}
	else 
	{jQuery('#bg').css("left",(-(wbg-wwindow)/2)+"px");}
	
}); **/