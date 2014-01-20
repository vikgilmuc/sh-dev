
var Vikslider = function() {
    
    this.loadPage();
    this.init();
    this.slider();
  // this.menu(jQuery("ul li"));

};

Vikslider.prototype = {
    
    
    init : function() {
       
        var self = this;
         self.wWindow = jQuery(document).width();
         self.hWindow = jQuery(window).height();
         self.nPanel = jQuery(" .panel").length;
        jQuery("body").removeClass("vikslider-no-js");
        jQuery("html").css("background", "none");
        jQuery("#parallax-bg1").css("display", "block");
        var e = jQuery("#parallax-bg1 .current").attr("href");
        var t = jQuery("#parallax-bg1 .panel img");
        var n = (this.e - 1) * self.wWindow;
        var r = self.hWindow - 0;
        var i = self.wWindow / r;
        var s = 1920 / 1080;
        jQuery("#parallax-bg1, #homePanels").height(r + "px");
        jQuery("#homePanels").width(self.wWindow * self.nPanel + "px").css({
            marginLeft : "-" + n + "px"
        });
        jQuery("#parallax-bg1 .panel, #parallax-bg1 .panel .info, #parallax-bg1 .panel .infoContent")
                .each(
                        function() {
                            jQuery(this).width(self.wWindow + "px").height(
                                    self.hWindow - 0 + "px")
                        });
        if (s > i) {
            t.height(r + "px").width("auto").css("marginTop", "0")
        } else {
            var o = t.height();
            var u = "-" + (o - r) / 2;
            t.height("auto").width(self.wWindow + "px").css("marginTop",
                    u + "px");
        }
    },

    homeAnimate : function(e, t) {},
 /*
        var self = this;
       // var wWindow = jQuery(document).width();
        //var hWindow = jQuery(window).height();
       var nPanel = jQuery(" .panel").length;
        if (!t) {
            var e = jQuery("#parallax-bg1 a.current").attr("href");
            if (e != self.nPanel)
                t = parseInt(e) + 1;
            else
                t = 1;

            jQuery("#parallax-bg1 a").removeClass("current");
            jQuery("#parallax-bg1 ul li:nth-child(" + t + ") a").addClass("current");
        }
        
       

        var n = 1200;
        var r = (t - 1) * self.wWindow;
       

        if (e < t) {
            var i = self.wWindow / 2;
            var s = "-" + i;
            var o = self.wWindow * 2;
        } else {
            var i = "-" + self.wWindow / 2;
            var s = "+" + self.wWindow / 2;
            var o = "-" + self.wWindow * 2;
        }
        
        jQuery("#homePanels").animate({
            marginLeft : "-" + r + "px"
        }, n, "easeInOutExpo");
        jQuery("#panel" + e + " img").animate({
            marginLeft : i + "px"
        }, n, "easeInOutExpo", function() {
            jQuery(this).css("marginLeft", "0")
        });
        jQuery("#panel" + e + " .info").animate({
            marginLeft : o + "px"
        }, n, "easeInOutExpo");
        jQuery("#panel" + t + " img").css({
            marginLeft : s + "px"
        }).animate({
            marginLeft : "0"
        }, n, "easeInOutExpo");
		
        jQuery("#panel" + t + " .info").css({
            marginLeft : "-" + o + "px"
        }).animate({
            marginLeft : "0"
        }, n, "easeInOutExpo");
        e = t
*/
    

    slider : function() {} ,
        /*
        var self = this;
        var e;
        e = "<ul>";
        for (i = 1; i <= self.nPanel; i++) {
            e += "<li>";
            e += '<a href="' + i + '">Slide ' + i + "</a>";
            e += "</li>";
        }
        e += "</ul>";
        jQuery("#parallax-bg1").append(e);
        jQuery("#parallax-bg1 ul a:first").addClass("current");
        jQuery("#parallax-bg1 ul").css("marginLeft",
                "-" + 14 * self.nPanel / 2 + "px");
        jQuery("#parallax-bg1 ul a").click(function() {
            clearTimeout(autoSlide);
            var e = jQuery(this);
            var t = e.attr("href");
            var n = jQuery("#parallax-bg1 a.current").attr("href");
            if (n != t) {
                jQuery("#parallax-bg1 ul a").removeClass("current");
                e.addClass("current");
                homeAnimate.call(Vikslider,n, t);
            }
            return false;
        });
        self.autoSlide = setInterval(function(){self.homeAnimate()}, 5e3);
    */
   

    loadPage : function () {},
        /*
        if(typeof autoSlide!=="undefined")clearTimeout(this.autoSlide);
        */

    
    menu : function(obj) { }
        /*
        
        var self = this;
        if (!obj.length)
            return;
        jQuery("body").removeClass("use-hover");
        jQuery(obj).hover(function() {
            jQuery(this).find('ul').slideDown(300, function() {
                jQuery(this).css({
                    overflow : "visible"
                });
            });
        }, 
		
		function() {
            jQuery(this).find('ul').slideUp(300);
        });*/
   
};

/*
jQuery(function() {
 
    // grab the initial top offset of the navigation 
    var sticky_navigation_offset_top = jQuery('#sticky_navigation').offset().top;
     
    // our function that decides weather the navigation bar should have "fixed" css position or not.
    var sticky_navigation = function(){
        var scroll_top = jQuery(window).scrollTop(); // our current vertical position from the top
         
        // if we've scrolled more than the navigation, change its position to fixed to stick to top,
        // otherwise change it back to relative
        if (scroll_top > sticky_navigation_offset_top) { 
            jQuery('#sticky_navigation').css({ 'position': 'fixed', 'z-index':'1', 'top':0, 'left':'100px' });
        } else {
            jQuery('#sticky_navigation').css({ 'position': 'relative' }); 
        }   
    };
     
    // run our function on load
    sticky_navigation();
     
    // and run it again every time you scroll
    jQuery(window).scroll(function() {
         sticky_navigation();
    });
 
});

*/
