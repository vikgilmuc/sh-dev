//**************************************************************************************************/
//	Lytebox v5.5
//
//	 Author: Markus F. Hay
//  Website: http://lytebox.com  (http://dolem.com/lytebox)
//	   Date: January 26, 2012
//	License: Creative Commons Attribution 3.0 License (http://creativecommons.org/licenses/by/3.0/)
//**************************************************************************************************/

//**************************************************************************************************/
// Lytebox constructor
//
//	@param	boolean		bInitialize	true to initialize the DOM, false to simply instantiate the object
//	@param	array		aHttp		array of HTTP objects, so that we don't lose references when Lytebox is reinitialized
//**************************************************************************************************/
	function Lytebox(bInitialize, aHttp) {
		
		// Below are the default settings that the lytebox viewer will inherit (look and feel, behavior) when displaying content. Member
		// properties that start with "__" can be manipulated via the data-lyte-options attribute (i.e. data-lyte-options="showPrint:true").
		
		/*** Language Configuration ***/
		
			// English - configure for your language or customize as needed. 
			// Note that these values will be seen by users when mousing over buttons.
			this.label = new Object();
			this.label['close']		= 'Close (Esc)';
			this.label['prev'] 		= 'Previous (\u2190)';	// Previous (left arrow)
			this.label['next'] 		= 'Next (\u2192)'; 		// Next (right arrow)
			this.label['play'] 		= 'Play (spacebar)';
			this.label['pause'] 	= 'Pause (spacebar)';
			this.label['print'] 	= 'Print';
			this.label['image'] 	= 'Image %1 of %2';		// %1 = active image, %2 = total images
			this.label['page'] 		= 'Page %1 of %2'; 		// %1 = active page, %2 = total pages
			
		
		/*** Configure Lytebox ***/
		
			this.theme   			= (typeof lyteboxTheme !== 'undefined') && /^(black|grey|red|green|blue|gold|orange)$/i.test(lyteboxTheme) ? lyteboxTheme : 'black'; // themes: black (default), grey, red, green, blue, gold, orange
			this.roundedBorder		= true; 		// controls whether or not the viewer uses rounded corners (false = square corners)
			this.innerBorder		= true;			// controls whether to show the inner border around image/html content
			this.outerBorder		= true;			// controls whether to show the outer grey (or theme) border
			this.resizeSpeed		= 5;			// controls the speed of the image resizing (1=slowest and 10=fastest)
			this.maxOpacity			= 80;			// higher opacity = darker overlay, lower opacity = lighter overlay
			this.borderSize			= 12;			// if you adjust the padding in the CSS, you will need to update this variable -- otherwise, leave this alone...
			this.appendQS			= false;		// if true, will append request_from=lytebox to the QS. Use this with caution as it may cause pages to not render
			this.fixedPosition		= this.isMobile() ? false : true;	// if true, viewer will remain in a fixed position, otherwise page scrolling will be allowed
			this.inherit			= true;			// controls whether or not data-lyte-options are inherited from the first link in a grouped set
			
			this.__hideObjects		= true;			// controls whether or not objects (such as Flash, Java, etc.) should be hidden when the viewer opens
			this.__autoResize		= true;			// controls whether or not images should be resized if larger than the browser window dimensions
			this.__doAnimations		= true;			// controls ALL animation effects (i.e. overlay fade in/out, image resize transition, etc.)
			this.__animateOverlay	= false;		// controls ONLY the overlay (background darkening) effects, and whether or not to fade in/out
			this.__forceCloseClick 	= false;		// if true, users are forced to click on the "Close" button when viewing content
			this.__refreshPage		= false;		// force page refresh after closing Lytebox
			this.__showPrint		= false;		// true to show print button, false to hide
			this.__navType			= 3;			// 1 = "Prev/Next" buttons on top left and left
													// 2 = "Prev/Next" buttons in navigation bar
													// 3 = navType_1 + navType_2 (show both)
														
			// These two options control the position of the title/counter and navigation buttons. Note that for mobile devices,
			// the title is displayed on top and the navigation on the bottom. This is due to the view area being limited.
			// You can customize this for non-mobile devices by changing the 2nd condition (: false) to true (: true)
			this.__navTop			= this.isMobile() ? false : false; // true to show the buttons on the top right, false to show them on bottom right (default)
			this.__titleTop			= this.isMobile() ? true : false;  // true to show the title on the top left, false to show it on the bottom left (default)
		
		
		/*** Configure HTML Content / Media Viewer Options ***/
		
			this.__width			= '80%';		// default width of content viewer
			this.__height			= '80%';		// default height of content viewer
			this.__scrolling		= 'auto';		// controls whether or not scrolling is allowed in the content viewer -- options are auto|yes|no
			this.__loopPlayback		= false;		// controls whether or not embedded media is looped (swf, avi, mov, etc.)
			this.__autoPlay			= true;			// controls whether or not to autoplay embedded media
			this.__autoEmbed		= true;			// controls whether or not to automatically embed media in an object tag
		
		
		/*** Configure Slideshow Options ***/
		
			this.__slideInterval	= 4000;			// change value (milliseconds) to increase/decrease the time between "slides"
			this.__showNavigation	= false;		// true to display Next/Prev buttons/text during slideshow, false to hide
			this.__showClose		= true;			// true to display the Close button, false to hide
			this.__showDetails		= true;			// true to display image details (caption, count), false to hide
			this.__showPlayPause	= true;			// true to display pause/play buttons next to close button, false to hide
			this.__autoEnd			= true;			// true to automatically close Lytebox after the last image is reached, false to keep open
			this.__pauseOnNextClick	= false;		// true to pause the slideshow when the "Next" button is clicked
			this.__pauseOnPrevClick = true;			// true to pause the slideshow when the "Prev" button is clicked
			this.__loopSlideshow	= false;		// true to continuously loop through slides, false otherwise
		
		
		/*** Configure Event Callbacks ***/
		
			this.__beforeStart		= '';			// function to call before the viewer starts
			this.__afterStart		= '';			// function to call after the viewer starts
			this.__beforeEnd		= '';			// function to call before the viewer ends (after close click)
			this.__afterEnd			= '';			// function to call after the viewer ends
		
			
		/*** Configure Lytetip (tooltips) Options ***/
			this.__changeTipCursor 	= true; 		// true to change the cursor to 'help', false to leave default (inhereted)
			this.__tipDecoration	= 'dotted';		// controls the text-decoration (underline) of the tip link (dotted|solid|none)
			this.__tipStyle 		= 'classic';	// sets the default tip style if none is specified via data-lyte-options. Possible values are classic, info, help, warning, error
			this.__tipRelative		= true;			// if true, tips will be positioned relative to the element. if false, tips will be absolutely positioned on the page.
													// if you are having issues with tooltips not being properly positioned, then set this to false

		
		
		
		// Hash for navType - by A.Popov http://s3blog.org
		this.navTypeHash = new Object();
		this.navTypeHash['Hover_by_type_1'] 	= true;
		this.navTypeHash['Display_by_type_1'] 	= false;
		this.navTypeHash['Hover_by_type_2'] 	= false;
		this.navTypeHash['Display_by_type_2']	= true;
		this.navTypeHash['Hover_by_type_3'] 	= true;
		this.navTypeHash['Display_by_type_3'] 	= true;
		
		// These will be used to clear the settimeout calls (release resources)
		this.resizeWTimerArray		= new Array();
		this.resizeWTimerCount		= 0;
		this.resizeHTimerArray		= new Array();
		this.resizeHTimerCount		= 0;
		this.changeContentTimerArray= new Array();
		this.changeContentTimerCount= 0;
		this.overlayTimerArray		= new Array();
		this.overlayTimerCount		= 0;
		this.imageTimerArray		= new Array();
		this.imageTimerCount		= 0;
		this.timerIDArray			= new Array();
		this.timerIDCount			= 0;
		this.slideshowIDArray		= new Array();
		this.slideshowIDCount		= 0;
		
		// These arrays will hold grouped content
		this.imageArray	 = new Array();
		this.slideArray	 = new Array();
		this.frameArray	 = new Array();
		this.contentNum = null;
		
		// This will store the page dimensions (results from calls to getPageSize())
		this.aPageSize = new Array();
		
		// This will be used to determine if the overlay has finished "appearing"
		this.overlayLoaded = false;
		
		// Check for iFrame environment (will set this.isFrame and this.doc member variables)
		this.checkFrame();
		
		// Set to false by default
		this.isSlideshow 	= false;
		this.isLyteframe 	= false;
		this.tipSet	 		= false;
		
		// We need to know the certain browser versions (or if it's IE) since IE is "special" and requires spoon feeding.
		this.ieVersion = this.ffVersion = this.chromeVersion = this.operaVersion = this.safariVersion = -1;
		this.ie = this.ff = this.chrome = this.opera = this.safari = false;
		this.setBrowserInfo();
		
		// Need to set the appropriate class attribute to query (because of IE).
		this.classAttribute = (((this.ie && this.doc.compatMode == 'BackCompat') || (this.ie && this.ieVersion <= 7)) ? 'className' : 'class');
		this.classAttribute = (this.ie && (document.documentMode == 8 || document.documentMode == 9)) ? 'class' : this.classAttribute; // IE8 & IE9 in standards mode
		
		// This will be used to determine if the viewer is ready to be displayed (DOM built) or not.
		this.isReady = false;
		
		// (09/13/2011) The following statements are only executed if we're initializing Lytebox (as opposed to creating the object for library functions only).
		if (bInitialize) {
			
			// Store our array of request objects, if there are any.
			this.http = aHttp;
			
			// (07/20/2011) Save last func for body.onscroll - fixed by A.Popov http://s3blog.org
			this.bodyOnscroll = document.body.onscroll;
			
			// Ensure that the resizeSpeed has an expected value.
			if(this.resizeSpeed > 10) { this.resizeSpeed = 10; }
			if(this.resizeSpeed < 1) { this.resizeSpeed = 1; }
			
			// Now calculate the resizeDuration, which varies from browser to browser, sadly. Opera defaults to 7 in the first check, BTW.
			var ie8Duration = 2; // If IE8 is too fast/jittery, increase this until it performs optimaly.
			var isWinXP = (navigator.userAgent.match(/windows nt 5.1/i) || navigator.userAgent.match(/windows nt 5.2/i) ? true : false);
			this.resizeDuration = (11 - this.resizeSpeed) * (this.ie ? (this.ieVersion >= 9 ? 6 : (this.ieVersion == 8 ? (this.doc.compatMode == 'BackCompat' ? ie8Duration : ie8Duration - 1) : 3)) : 7);
			this.resizeDuration = this.ff ? (11 - this.resizeSpeed) * (this.ffVersion < 6 ? 3 : (isWinXP ? 6 : 12)) : this.resizeDuration;
			this.resizeDuration = this.chrome ? (11 - this.resizeSpeed) * 5 : this.resizeDuration;
			this.resizeDuration = this.safari ? (11 - this.resizeSpeed) * 20 : this.resizeDuration;
			this.resizeDuration = this.isMobile() ? (11 - this.resizeSpeed) * 2 : this.resizeDuration;
		
			// Now initialize the DOM.
			// 09/30/2011 - Bypass this if we're loading the Lytebox library from within a Lytebox enabled HTML content viewer. If we allow this,
			//		then the viewer will be closed since the DOM reinitializes and resets the lbIframe container.
			if (window.name != 'lbIframe') {
				this.initialize();
			}
			
		// Otherwise, we're going to initialize some global variables.
		} else {
			
			// This will be used to keep track of unique request objects.
			this.http = new Array();
			
			// Define a short-hand version of document.getElementById() with caching, if one doesn't exist.
			// TODO: Write a DOM object wrapper so we can extend $ to have access to our internal library functions (chained)
			if (typeof $ == 'undefined') {
				$ = function (id) {
						if ($.cache[id] === undefined) {
							$.cache[id] = document.getElementById(id) || false;
						}
						return $.cache[id];
					};
				$.cache = {};
			}
			
		}
		
	} // Lytebox()

//*************************************************************************************************/
// setBrowserInfo() - Determines browser vendor and major version. Currently we keep track of the
//		the top 99.7% of browsers in use, which as of this writing includes Chrome, Firefox, Safari,
//		Internet Explorer, and Opera.
//*************************************************************************************************/
	Lytebox.prototype.setBrowserInfo = function() {
		
		var ua = navigator.userAgent.toLowerCase();
		
		this.chrome = ua.indexOf('chrome') > -1;
		this.ff = ua.indexOf('firefox') > -1;
		this.safari = !this.chrome && ua.indexOf('safari') > -1;
		this.opera = ua.indexOf('opera') > -1;
		this.ie = /*@cc_on!@*/false;
		
		// Set up the version for Chrome.
		if (this.chrome) {
			var re = new RegExp("chrome/([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null) {
				this.chromeVersion = parseInt( RegExp.$1 );
			}
		}		
		// Set up the version for Firefox.
		if (this.ff) {
			var re = new RegExp("firefox/([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null) {
				this.ffVersion = parseInt( RegExp.$1 );
			}
		}		
		// Set up the version for IE.
		if (this.ie) {
			var re = new RegExp("msie ([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null) {
				this.ieVersion = parseInt( RegExp.$1 );
			}
		}		
		// Set up the version for Opera.
		if (this.opera) {
			var re = new RegExp("opera/([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null) {
				this.operaVersion = parseInt( RegExp.$1 );
			}
		}		
		// Set up the version for Safari.
		if (this.safari) {
			var re = new RegExp("version/([0-9]{1,}[\.0-9]{0,})");
			if (re.exec(ua) != null) {
				this.safariVersion = parseInt( RegExp.$1 );
			}
		}
		
	}; // setBrowserInfo()

//********************************************************************************************/
// initialize() - Constructor runs on completion of the DOM loading. This function is responsible
//		for adding elements to the page that are used to display the content, or in other words,
//		this is actually building the DOM for Lytebox itself.
//********************************************************************************************/
	Lytebox.prototype.initialize = function() {
		
		// Make a call to get all the images on the page.
		this.updateLyteboxItems();
		
		// The rest of this code inserts html at the bottom of the page that looks similar to this:
		//
		//	<a href="#" id="lbLauncher"></a>
		//	<div id="lbOverlay"></div>
		//	<div id="lbMain">
		//		<div id="lbOuterContainer">
		//
		//			<div id="lbTopContainer">
		//				<div id="lbTopData">
		//					<span id="lbTitleTop"></span>
		//					<span id="lbNumTop"></span>
		//				</div>
		//				<div id="lbTopNav">
		//					<a href="void(0);" id="lbPrevTop"></a>
		//					<a href="void(0);" id="lbPlayTop"></a>
		//					<a href="void(0);" id="lbPauseTop"></a>
		//					<a href="void(0);" id="lbNextTop"></a>
		//					<a href="void(0);" id="lbPrintTop"></a>
		//					<a href="void(0);" id="lbCloseTop"></a>
		//				</div>
		//			</div>
		//
		//			<div id="lbImageContainer">
		//				<img id="lbImage">
		//				<div style="" id="lbHoverNav">
		//					<a href="void(0);" id="lbPrevHov"></a>
		//					<a href="void(0);" id="lbNextHov"></a>
		//				</div>
		//			</div>
		//			<div id="lbIframeContainer">
		//				<iframe id="lbIframe" ... ></iframe>
		//			</div>
		//
		//			<div id="lbLoading"></div>
		//
		//			<div id="lbBottomContainer">
		//				<div id="lbBottomData">
		//					<span id="lbTitleBottom"></span>
		//					<span id="lbNumBottom"></span>
		//					<span id="lbDescBottom"></span>
		//				</div>
		//				<div id="lbBottomNav">
		//					<a href="void(0);" id="lbPrev"></a>
		//					<a href="void(0);" id="lbPlay"></a>
		//					<a href="void(0);" id="lbPause"></a>
		//					<a href="void(0);" id="lbNext"></a>
		//					<a href="void(0);" id="lbPrint"></a>
		//					<a href="void(0);" id="lbClose"></a>
		//				</div>
		//			</div>
		//
		//		</div>
		//	</div>
	
		var oBody = this.doc.getElementsByTagName('body').item(0);
		
		// Remove these (if they exist) to prevent duplication.
		if (this.doc.$('lbOverlay')) { oBody.removeChild(this.doc.$('lbOverlay')); }
		if (this.doc.$('lbMain')) {	oBody.removeChild(this.doc.$('lbMain')); }
		if (this.doc.$('lbLauncher')) { oBody.removeChild(this.doc.$('lbLauncher')); }
		
		var oLauncher = this.doc.createElement('a');
			oLauncher.setAttribute('id','lbLauncher');
			oLauncher.setAttribute(this.classAttribute, 'lytebox');
			oLauncher.style.display = 'none';
			oBody.appendChild(oLauncher);
	
		var oOverlay = this.doc.createElement('div');
			oOverlay.setAttribute('id','lbOverlay');
			oOverlay.setAttribute(this.classAttribute, this.theme);
			// Change overlay position to absolute for IE6 and below, and IE7 with no DOCTYPE, since a fixed position screws everything up for these.
			if (this.ie && (this.ieVersion <= 6 || (this.ieVersion <= 9 && this.doc.compatMode == 'BackCompat'))) {
				oOverlay.style.position = 'absolute';
			}
			oOverlay.style.display = 'none';
			oBody.appendChild(oOverlay);
		
		var oLytebox = this.doc.createElement('div');
			oLytebox.setAttribute('id','lbMain');
			oLytebox.style.display = 'none';
			oBody.appendChild(oLytebox);
		
		var oOuterContainer = this.doc.createElement('div');
			oOuterContainer.setAttribute('id','lbOuterContainer');
			oOuterContainer.setAttribute(this.classAttribute, this.theme);
			if (this.roundedBorder) {
				oOuterContainer.style.MozBorderRadius = '8px';
				oOuterContainer.style.borderRadius = '8px';
			}
			oLytebox.appendChild(oOuterContainer);
			
		// TOP CONTAINER (title and close button, if activated)
		var oTopContainer = this.doc.createElement('div');
			oTopContainer.setAttribute('id','lbTopContainer');
			oTopContainer.setAttribute(this.classAttribute, this.theme);
			if (this.roundedBorder) {
				oTopContainer.style.MozBorderRadius = '8px';
				oTopContainer.style.borderRadius = '8px';
			}
			oOuterContainer.appendChild(oTopContainer);
			
		var oTopData = this.doc.createElement('div');
			oTopData.setAttribute('id','lbTopData');
			oTopData.setAttribute(this.classAttribute, this.theme);
			oTopContainer.appendChild(oTopData);
			
		var oTitleTop = this.doc.createElement('span');
			oTitleTop.setAttribute('id','lbTitleTop');
			oTopData.appendChild(oTitleTop);
			
		var oNumTop = this.doc.createElement('span');
			oNumTop.setAttribute('id','lbNumTop');
			oTopData.appendChild(oNumTop);
			
		var oTopNav = this.doc.createElement('div');
			oTopNav.setAttribute('id','lbTopNav');
			oTopContainer.appendChild(oTopNav);
			
		var oCloseTop = this.doc.createElement('a');
			oCloseTop.setAttribute('id','lbCloseTop');
			oCloseTop.setAttribute('title', this.label['close']);
			oCloseTop.setAttribute(this.classAttribute, this.theme);
			oCloseTop.setAttribute('href','javascript:void(0)');
			oTopNav.appendChild(oCloseTop);
			
		var oPrintTop = this.doc.createElement('a');
			oPrintTop.setAttribute('id','lbPrintTop')
			oPrintTop.setAttribute('title', this.label['print']);
			oPrintTop.setAttribute(this.classAttribute, this.theme);
			oPrintTop.setAttribute('href','javascript:void(0)');
			oTopNav.appendChild(oPrintTop);
			
		var oNextTop = this.doc.createElement('a');
			oNextTop.setAttribute('id','lbNextTop');
			oNextTop.setAttribute('title', this.label['next']);
			oNextTop.setAttribute(this.classAttribute, this.theme);
			oNextTop.setAttribute('href','javascript:void(0)');
			oTopNav.appendChild(oNextTop);
			
		var oPauseTop = this.doc.createElement('a');
			oPauseTop.setAttribute('id','lbPauseTop');
			oPauseTop.setAttribute('title', this.label['pause']);
			oPauseTop.setAttribute(this.classAttribute, this.theme);
			oPauseTop.setAttribute('href','javascript:void(0)');
			oPauseTop.style.display = 'none';
			oTopNav.appendChild(oPauseTop);
			
		var oPlayTop = this.doc.createElement('a');
			oPlayTop.setAttribute('id','lbPlayTop');
			oPlayTop.setAttribute('title', this.label['play']);
			oPlayTop.setAttribute(this.classAttribute, this.theme);
			oPlayTop.setAttribute('href','javascript:void(0)');
			oPlayTop.style.display = 'none';
			oTopNav.appendChild(oPlayTop);
			
		var oPrevTop = this.doc.createElement('a');
			oPrevTop.setAttribute('id','lbPrevTop');
			oPrevTop.setAttribute('title', this.label['prev']);
			oPrevTop.setAttribute(this.classAttribute, this.theme);
			oPrevTop.setAttribute('href','javascript:void(0)');
			oTopNav.appendChild(oPrevTop);
		// TOP CONTAINER
			
		var oIframeContainer = this.doc.createElement('div');
			oIframeContainer.setAttribute('id','lbIframeContainer');
			oIframeContainer.style.display = 'none';
			oOuterContainer.appendChild(oIframeContainer);
			
		var oIframe = this.doc.createElement('iframe');
			oIframe.setAttribute('id','lbIframe');
			oIframe.setAttribute('name','lbIframe')
			oIframe.setAttribute('frameBorder','0');
			if (this.innerBorder) {
				oIframe.setAttribute(this.classAttribute, this.theme);
			}
			oIframe.style.display = 'none';
			oIframeContainer.appendChild(oIframe);
	
		var oImageContainer = this.doc.createElement('div');
			oImageContainer.setAttribute('id','lbImageContainer');
			oOuterContainer.appendChild(oImageContainer);
	
		var oLyteboxImage = this.doc.createElement('img');
			oLyteboxImage.setAttribute('id','lbImage');
			if (this.innerBorder) {
				oLyteboxImage.setAttribute(this.classAttribute, this.theme);
			}
			oImageContainer.appendChild(oLyteboxImage);
			
		var oLoading = this.doc.createElement('div');
			oLoading.setAttribute('id','lbLoading');
			oLoading.setAttribute(this.classAttribute, this.theme);
			oOuterContainer.appendChild(oLoading);
			
		var oBottomContainer = this.doc.createElement('div');
			oBottomContainer.setAttribute('id','lbBottomContainer');
			oBottomContainer.setAttribute(this.classAttribute, this.theme);
			if (this.roundedBorder) {
				oBottomContainer.style.MozBorderRadius = '8px';
				oBottomContainer.style.borderRadius = '8px';
			}
			oOuterContainer.appendChild(oBottomContainer);
		
		var oDetailsBottom = this.doc.createElement('div');
			oDetailsBottom.setAttribute('id','lbBottomData');
			oDetailsBottom.setAttribute(this.classAttribute, this.theme);
			oBottomContainer.appendChild(oDetailsBottom);
	
		var oTitleBottom = this.doc.createElement('span');
			oTitleBottom.setAttribute('id','lbTitleBottom');
			oDetailsBottom.appendChild(oTitleBottom);
			
		var oNumBottom = this.doc.createElement('span');
			oNumBottom.setAttribute('id','lbNumBottom');
			oDetailsBottom.appendChild(oNumBottom);
			
		var oDescBottom = this.doc.createElement('span');
			oDescBottom.setAttribute('id','lbDescBottom');
			oDetailsBottom.appendChild(oDescBottom);
			
		var oHoverNav = this.doc.createElement('div');
			oHoverNav.setAttribute('id','lbHoverNav');
			oImageContainer.appendChild(oHoverNav);
		
		var oBottomNav = this.doc.createElement('div');
			oBottomNav.setAttribute('id','lbBottomNav');
			oBottomContainer.appendChild(oBottomNav);
		
		var oPrevHov = this.doc.createElement('a');
			oPrevHov.setAttribute('id','lbPrevHov');
			oPrevHov.setAttribute('title', this.label['prev']);
			oPrevHov.setAttribute(this.classAttribute, this.theme);
			oPrevHov.setAttribute('href','javascript:void(0)');
			oHoverNav.appendChild(oPrevHov);
		
		var oNextHov = this.doc.createElement('a');
			oNextHov.setAttribute('id','lbNextHov');
			oNextHov.setAttribute('title', this.label['next']);
			oNextHov.setAttribute(this.classAttribute, this.theme);
			oNextHov.setAttribute('href','javascript:void(0)');
			oHoverNav.appendChild(oNextHov);
	
		var oClose = this.doc.createElement('a');
			oClose.setAttribute('id','lbClose');
			oClose.setAttribute('title', this.label['close']);
			oClose.setAttribute(this.classAttribute, this.theme);
			oClose.setAttribute('href','javascript:void(0)');
			oBottomNav.appendChild(oClose);
			
		var oPrint = this.doc.createElement('a');
			oPrint.setAttribute('id','lbPrint');
			oPrint.setAttribute('title', this.label['print']);
			oPrint.setAttribute(this.classAttribute, this.theme);
			oPrint.setAttribute('href','javascript:void(0)');
			oPrint.style.display = 'none';
			oBottomNav.appendChild(oPrint);
			
		var oNext = this.doc.createElement('a');
			oNext.setAttribute('id','lbNext');
			oNext.setAttribute('title', this.label['next']);
			oNext.setAttribute(this.classAttribute, this.theme);
			oNext.setAttribute('href','javascript:void(0)');
			oBottomNav.appendChild(oNext);
			
		var oPause = this.doc.createElement('a');
			oPause.setAttribute('id','lbPause');
			oPause.setAttribute('title', this.label['pause']);
			oPause.setAttribute(this.classAttribute, this.theme);
			oPause.setAttribute('href','javascript:void(0)');
			oPause.style.display = 'none';
			oBottomNav.appendChild(oPause);
			
		var oPlay = this.doc.createElement('a');
			oPlay.setAttribute('id','lbPlay');
			oPlay.setAttribute('title', this.label['play']);
			oPlay.setAttribute(this.classAttribute, this.theme);
			oPlay.setAttribute('href','javascript:void(0)');
			oPlay.style.display = 'none';
			oBottomNav.appendChild(oPlay);
			
		var oPrev = this.doc.createElement('a');
			oPrev.setAttribute('id','lbPrev');
			oPrev.setAttribute('title', this.label['prev']);
			oPrev.setAttribute(this.classAttribute, this.theme);
			oPrev.setAttribute('href','javascript:void(0)');
			oBottomNav.appendChild(oPrev);
			
		// Now loop through iframe elements and detect YouTube videos. If found, add wmode=transparent to the URL.
		// This is necessary because YouTube videos appear above other content.
		var iframes = (this.isFrame && window.parent.frames[window.name].document) ? window.parent.frames[window.name].document.getElementsByTagName('iframe') : document.getElementsByTagName('iframe');
		for (var i = 0; i < iframes.length; i++) {
			if (/youtube/i.test(iframes[i].src)) {
				iframes[i].src += ((/\?/.test(iframes[i].src)) ? '&' : '?') + 'wmode=transparent';
			}
		}
		
		// Set this so that we know the viewer is ready.
		this.isReady = true;
			
	}; // initialize()

//********************************************************************************/
// updateLyteboxItems() - Loops through anchor and area tags looking for 'lytebox' 
//		references, adding onclick events to the appropriate links.
//********************************************************************************/
	Lytebox.prototype.updateLyteboxItems = function() {
		
		// Populate arrays of anchors and area maps from the appropriate window (could be the parent or iframe document)
		// (07/20/2011) anchors = null fix provided by A.Popov http://s3blog.org, slightly modified by Markus Hay
		var anchors = (this.isFrame && window.parent.frames[window.name].document) ? window.parent.frames[window.name].document.getElementsByTagName('a') : document.getElementsByTagName('a');
			anchors = (this.isFrame) ? anchors : document.getElementsByTagName('a');
		var areas = (this.isFrame && window.parent.frames[window.name].document) ? window.parent.frames[window.name].document.getElementsByTagName('area') : document.getElementsByTagName('area');
		
		// Combine both into a single array.
		var lyteLinks = this.combine(anchors, areas);
		
		// Initialize local variables used.
		var myLink = relAttribute = revAttribute = classAttribute = dataOptions = dataTip = tipDecoration = tipStyle = tipImage = tipHtml = aSetting = sName = sValue = sExt = aUrl = null;
		var bImage = bRelative = false;
		
		// Now loop through all Lytebox enabled links
		for (var i = 0; i < lyteLinks.length; i++) {
			myLink = lyteLinks[i];
			
			// For backwards compatibility we'll store the value of the "rel" attribute.
			relAttribute = String(myLink.getAttribute('rel'));
			classAttribute = String(myLink.getAttribute(this.classAttribute));
			
			// Use the string.match() method to catch 'lytebox' references in the rel attribute
			if (myLink.getAttribute('href')) {
				
				// Determine the viewer type. Return value will be null if none of these are specified. The checks for lyteshow/lyteframe 
				// are for backwards compatibility, as well as the old "rel" attribute (for those wishing to validate).
				sType = classAttribute.match(/lytebox|lyteshow|lyteframe/i);
				sType = this.isEmpty(sType) ? relAttribute.match(/lytebox|lyteshow|lyteframe/i) : sType;
				
				// Get the options used in this link.
				// For backwards compatibility, check for the "REV" attribute if "data-lyte-options" doesn't exist.
				dataOptions = String(myLink.getAttribute('data-lyte-options'));
				dataOptions = this.isEmpty(dataOptions) ? String(myLink.getAttribute('rev')) : dataOptions;
				
				// Detemine the type of content in the URL. It's either an image or iframe content.
				aUrl = myLink.getAttribute('href').split('?');
				sExt = aUrl[0].split('.').pop().toLowerCase();
				bImage = (sExt == 'png' || sExt == 'jpg' || sExt == 'jpeg' || sExt == 'gif' || sExt == 'bmp');
				
				// Now set the appropriate onclick handler. Note that we are retaining checks for lyteshow/lyteframe for backwards compatibility.
				if (sType && sType.length >= 1) {
					// If the link is a YouTube video and it's a mobile device we don't assign an onclick handler and instead set the target to _blank.
					// This will effectively launch the devices YouTube player and get around all the iframe issues that plague mobile devices.
					if (this.isMobile() && /youtube/i.test(myLink.getAttribute('href'))) {
						myLink.target = '_blank';
					// Slideshow - check to determine if it's an image and whether or not we're doing a slideshow.
					} else if (bImage && (dataOptions.match(/slide:true/i) || sType[0].toLowerCase() == 'lyteshow')) {
						myLink.onclick = function () { $lb.start(this, true, false); return false; }
					// Image - image viewer only (no slideshow).
					} else if (bImage) {
						myLink.onclick = function () { $lb.start(this, false, false); return false; }
					// iFrame - HTML/embedded media content
					} else {
						myLink.onclick = function () { $lb.start(this, false, true); return false; }
					}
				}
					
				// Lytetip - Note that if data-tip is empty, then we check for the value of the title attribute for those wishing to
				// ensure that their pages still validate.
				dataTip = String(myLink.getAttribute('data-tip'));
				dataTip = this.isEmpty(dataTip) ? myLink.getAttribute('title') : dataTip;
				
				// Now determine if the link should be tooltipified.
				if (classAttribute.toLowerCase().match('lytetip') && !this.isEmpty(dataTip) && !this.tipsSet) {
			
					// We'll go ahead and set some defaults in the event that the user doesn't have any attributes set.
					if (this.__changeTipCursor) { myLink.style.cursor = 'help'; }
					tipDecoration = this.__tipDecoration;
					tipStyle = this.__tipStyle;
					bRelative = this.__tipRelative;
					
					// If the user is setting custom attributes, loop to determine what they and set the appropriate classes.
					if (!this.isEmpty(dataOptions)) {						
						// Loop through our options so that we can set the appropriate tip attributes.
						aOptions = dataOptions.split(' ');
						for (var j = 0; j < aOptions.length; j++) {
							// The option itself should be a name:value pair, so split into what should be a two element array.
							aSetting = aOptions[j].split(':');
							sName = (aSetting.length > 1 ? this.trim(aSetting[0]).toLowerCase() : '');
							sValue = (aSetting.length > 1 ? this.trim(aSetting[1]) : '');
							
							// Now we'll run some tests to determine which particular option is being set, and what the value should be.
							switch(sName) {
								case 'tipstyle':
									tipStyle = (/classic|info|help|warning|error/.test(sValue) ? sValue : tipStyle); break;
								case 'changetipcursor':
									myLink.style.cursor = (/true|false/.test(sValue) ? (sValue == 'true' ? 'help' : '') : myLink.style.cursor); break;
								case 'tiprelative':
									bRelative = (/true|false/.test(sValue) ? (sValue == 'true') : bRelative); break;
								case 'tipdecoration':
									tipDecoration = (/dotted|solid|none/.test(sValue) ? sValue : tipDecoration); break;
							}
						}
					}
					
					// Set the appropriate border style for the tooltip link. Dotted is the default, so we don't need to set that.
					if (tipDecoration != 'dotted') {
						myLink.style.borderBottom = (tipDecoration == 'solid' ? '1px solid' : 'none');
					}
					
					// Now that the tip style has been determine, we'll set the appropriate classes.
					switch(tipStyle) {
						case 'info': tipStyle = 'lbCustom lbInfo'; tipImage = 'lbTipImg lbInfoImg'; break;
						case 'help': tipStyle = 'lbCustom lbHelp'; tipImage = 'lbTipImg lbHelpImg'; break;
						case 'warning': tipStyle = 'lbCustom lbWarning'; tipImage = 'lbTipImg lbWarningImg'; break;
						case 'error': tipStyle = 'lbCustom lbError'; tipImage = 'lbTipImg lbErrorImg'; break;
						case 'classic': tipStyle = 'lbClassic'; tipImage = ''; break;
						default: tipStyle = 'lbClassic'; tipImage = '';
					}	
					
					// IE6, IE7, and IE8 (QuirksMode) do not properly position the custom image outside the container, so we'll blank out the image for now.
					if ((this.ie && this.ieVersion <= 7) || (this.ieVersion == 8 && this.doc.compatMode == 'BackCompat')) {
						tipImage = '';
						if (tipStyle != 'lbClassic' && !this.isEmpty(tipStyle)) {
							tipStyle += ' lbIEFix';
						}
					}
					
					// Find the position of the object in question so that we can set the tooltip position accordingly.
					var aLinkPos = this.findPos(myLink);
					
					// Fix for IE in QuirksMode, which does not properly position the tooltip.
					if ((this.ie && (this.ieVersion <= 6 || this.doc.compatMode == 'BackCompat')) || bRelative) {
						myLink.style.position = 'relative';
					}
					
					// Clear out just in case Lytebox is being re-initialized.
					tipHtml = myLink.innerHTML;
					myLink.innerHTML = '';
					if ((this.ie && this.ieVersion <= 6 && this.doc.compatMode != 'BackCompat') || bRelative) {
						myLink.innerHTML = tipHtml + '<span class="' + tipStyle + '">' + (tipImage ? '<div class="' + tipImage + '"></div>' : '') + dataTip + '</span>';
					} else {
						myLink.innerHTML = tipHtml + '<span class="' + tipStyle + '" style="left:'+aLinkPos[0]+'px;top:'+(aLinkPos[1]+aLinkPos[2])+'px;">' + (tipImage ? '<div class="' + tipImage + '"></div>' : '') + dataTip + '</span>';
					}
					
					// Clear the title attribute to prevent overlapping browser tips, but only if the current link is not a "Lytebox" viewer enabled link.
					if (classAttribute.match(/lytebox|lyteshow|lyteframe/i) == null) {
						myLink.setAttribute('title','');
					}
				}
			}
		}
		this.tipsSet = true;
		
	}; // updateLyteboxItems()
	
//******************************************************************************************/
// launch() - Launches the Lytebox image or HTML content viewer programmatically,
//		as in the user can call this function to launch Lytebox from their own JS.
//		The content stype is automatically detected (either image or not).
//
//	@param	object	args	An object with properties to set the url, title, etc.
//							The following properties are supported:
//
//							url			string		The image path/url, or url to HTML content
//							options		string		data-lyte-options values
//							title		string		data-title value
//							description	string		data-description value
//******************************************************************************************/
	Lytebox.prototype.launch = function(args) {
		
		// Initialize.
		var sUrl = this.isEmpty(args.url) ? '' : String(args.url);
		var sOptions = this.isEmpty(args.options) ? '' : String(args.options);
		var sTitle = this.isEmpty(args.title) ? '' : args.title;
		var sDesc = this.isEmpty(args.description) ? '' : args.description;
		
		// Determine if this is a slideshow.
		var bSlideshow = /slide:true/i.test(sOptions);
		
		// Ensure that we have a URL. If not, just return false immediately.
		if (this.isEmpty(sUrl)) {
			return false;
		}
		
		// Before we proceed, we need to ensure that the Lytebox viewer is ready (DOM built). If not
		// then we make a callback to ourselves after 1/10th of a second.
		if (!this.isReady) {
			this.timerIDArray[this.timerIDCount++] = setTimeout("$lb.launch({ url: '" + sUrl + "', options: '" + sOptions + "', title: '" + sTitle + "', description: '" + sDesc + "' })", 100);
			return;
		} else {
			for (var i = 0; i < this.timerIDCount; i++) { window.clearTimeout(this.timerIDArray[i]); }
		}
		
		// First we need to detemine the type of content in the URL. It's either an image or iframe content.
		var aUrl = sUrl.split('?');
		var sExt = aUrl[0].split('.').pop().toLowerCase();
		var bImage = (sExt == 'png' || sExt == 'jpg' || sExt == 'jpeg' || sExt == 'gif' || sExt == 'bmp');
		
		// Set up the lbLauncher link, then start it up.
		var oLauncher = this.doc.$('lbLauncher');
			oLauncher.setAttribute('href', sUrl);
			oLauncher.setAttribute('data-lyte-options', sOptions);
			oLauncher.setAttribute('data-title', sTitle);
			oLauncher.setAttribute('data-description', sDesc);
		this.updateLyteboxItems();
		this.start(oLauncher, bSlideshow, (bImage ? false : true));
		
	}; // launch()

//********************************************************************************/
// start() - Sets appropriate options (data-lyte-options) and starts the viewer.
//
//	@param	object	oLink			The lytebox enabled element
//	@param	boolean	bSlideshow		true for slideshow, false otherwise
//	@param	boolean	bFrame			true for iframe content, false otherwise
//********************************************************************************/
	Lytebox.prototype.start = function(oLink, bSlideshow, bFrame) {
		
		// Read the data attributes (from either data-lyte-options or rev) into a local variable.
		var dataOptions = String(oLink.getAttribute('data-lyte-options'));
			dataOptions = this.isEmpty(dataOptions) ? String(oLink.getAttribute('rev')) : dataOptions;
		
		// Set the configuration options for the viewer.
		this.setOptions(dataOptions);
		
		// Set these so that they can be used throughout the startup process.
		this.isSlideshow = (bSlideshow ? true : false);
		this.isLyteframe = (bFrame ? true : false);
		
		// See if a "before start" handler is set and execute that first.
		if (!this.isEmpty(this.beforeStart)) {
			// Verify that this is a valid function, and if so call it. If the return value equates to false the viewer will remain open.
			var callback = window[this.beforeStart];
			if (typeof callback === 'function') {
				if (!callback(this.args)) { return; }
			}
		}
		
		// Hide select boxes for IE6 and below
		if (this.ie && this.ieVersion <= 6) { this.toggleSelects('hide'); }
		
		// Hide objects (should add a boolean to disable this)
		if (this.hideObjects) { this.toggleObjects('hide'); }
		
		// If we're dealing with frames instead of images, set the appropriate container to print.
		if (this.isFrame && window.parent.frames[window.name].document) {
			window.parent.$lb.printId = (this.isLyteframe ? 'lbIframe' : 'lbImage');
		} else {
			this.printId = (this.isLyteframe ? 'lbIframe' : 'lbImage');
		}
		
		// stretch overlay to fill page and fade in
		this.aPageSize	= this.getPageSize();
		var objOverlay	= this.doc.$('lbOverlay');
		var objBody		= this.doc.getElementsByTagName("body").item(0);
		
		objOverlay.style.height = this.aPageSize[1] + "px";
		objOverlay.style.display = '';
		
		// If animations are enabled, then fade in the overlay, otherwise set it to this.maxOpacity.
		this.fadeIn({ id: 'lbOverlay', opacity: (this.doAnimations && this.animateOverlay && (!this.ie || this.ieVersion >= 9) ? 0 : this.maxOpacity) });
		
		// Get a reference to our anchor and area tags so we can use them below.
		var anchors = (this.isFrame && window.parent.frames[window.name].document) ? window.parent.frames[window.name].document.getElementsByTagName('a') : document.getElementsByTagName('a');
			anchors = (this.isFrame) ? anchors : document.getElementsByTagName('a');
		var areas = (this.isFrame && window.parent.frames[window.name].document) ? window.parent.frames[window.name].document.getElementsByTagName('area') : document.getElementsByTagName('area');
		
		// Combine both into a single array.
		var lyteLinks = this.combine(anchors, areas);
		
		// Initialize.
		var sType = sExt = aUrl = null;
		this.frameArray = [];
		this.frameNum = 0;
		this.imageArray = [];
		this.imageNum = 0;
		this.slideArray = [];
		this.slideNum = 0;
		
		// Update the appropriate array for non grouped content.
		if (this.isEmpty(this.group)) {
			// Read the data attributes (from either data-lyte-options or rev) into a local variable.
			dataOptions = String(oLink.getAttribute('data-lyte-options'));
			dataOptions = this.isEmpty(dataOptions) ? String(oLink.getAttribute('rev')) : dataOptions;
			
			// HTML content or embedded media.
			if (this.isLyteframe) {			
				this.frameArray.push(new Array(oLink.getAttribute('href'), (!this.isEmpty(oLink.getAttribute('data-title')) ? oLink.getAttribute('data-title') : oLink.getAttribute('title')), oLink.getAttribute('data-description'), dataOptions));
			// Good 'old fashioned images.
			} else {
				this.imageArray.push(new Array(oLink.getAttribute('href'), (!this.isEmpty(oLink.getAttribute('data-title')) ? oLink.getAttribute('data-title') : oLink.getAttribute('title')), oLink.getAttribute('data-description'), dataOptions));
			}
			
		// Otherwise, content is being grouped.
		} else {
			
			// Loop through lytebox enabled links to find content that is part of the set.
			for (var i = 0; i < lyteLinks.length; i++) {
				var myLink = lyteLinks[i];
				
				// Get the data options, noting that these will come from the "data-lyte-options" attribute by default, or the
				// "rev" attribute if that doesn't exist.
				dataOptions = String(myLink.getAttribute('data-lyte-options'));
				dataOptions = this.isEmpty(dataOptions) ? String(myLink.getAttribute('rev')) : dataOptions;
				
				// Determine if a group attribute is set and whether it belongs to the group of the link clicked. 
				// If so, then we'll add it to the appropriate array (frameArray, slideArray, or imageArray).
				if (myLink.getAttribute('href') && dataOptions.toLowerCase().match('group:' + this.group)) {
					// Determine the viewer type. Return value will be null if none of these are specified. The checks for lyteshow/lyteframe 
					// are for backwards compatibility, as well as the old "rel" attribute (for those wishing to validate).
					sType = String(myLink.getAttribute(this.classAttribute)).match(/lytebox|lyteshow|lyteframe/i);
					sType = this.isEmpty(sType) ? myLink.getAttribute('rel').match(/lytebox|lyteshow|lyteframe/i) : sType;
					
					// Detemine the type of content in the URL. It's either an image or iframe content.
					aUrl = myLink.getAttribute('href').split('?');
					sExt = aUrl[0].split('.').pop().toLowerCase();
					bImage = (sExt == 'png' || sExt == 'jpg' || sExt == 'jpeg' || sExt == 'gif' || sExt == 'bmp');
					
					// Now set the appropriate onclick handler. Note that we are retaining checks for lyteshow/lyteframe for backwards compatibility.
					if (sType && sType.length >= 1) {
						// Slideshow - check to determine if it's an image and whether or not we're doing a slideshow.
						if (bImage && (dataOptions.match(/slide:true/i) || sType[0].toLowerCase() == 'lyteshow')) {
							this.slideArray.push(new Array(myLink.getAttribute('href'), (!this.isEmpty(myLink.getAttribute('data-title')) ? myLink.getAttribute('data-title') : myLink.getAttribute('title')), myLink.getAttribute('data-description'), dataOptions));
						// Image - image viewer only (no slideshow).
						} else if (bImage) {
							this.imageArray.push(new Array(myLink.getAttribute('href'), (!this.isEmpty(myLink.getAttribute('data-title')) ? myLink.getAttribute('data-title') : myLink.getAttribute('title')), myLink.getAttribute('data-description'), dataOptions));
						// iFrame - HTML/embedded media content
						} else {
							this.frameArray.push(new Array(myLink.getAttribute('href'), (!this.isEmpty(myLink.getAttribute('data-title')) ? myLink.getAttribute('data-title') : myLink.getAttribute('title')), myLink.getAttribute('data-description'), dataOptions));
						}
					}
				}
			}
			
			// Now remove duplicates from our array and update the correct frame/slide/image number.
			if (this.isLyteframe) {
				this.frameArray = this.removeDuplicates(this.frameArray);
				while(this.frameArray[this.frameNum][0] != oLink.getAttribute('href')) { this.frameNum++; }
			} else if (bSlideshow) {
				this.slideArray = this.removeDuplicates(this.slideArray);
				try {
					while(this.slideArray[this.slideNum][0] != oLink.getAttribute('href')) { this.slideNum++; }
				} catch(e) {
					//alert(e.description);
				}
			} else {
				this.imageArray = this.removeDuplicates(this.imageArray);
				while(this.imageArray[this.imageNum][0] != oLink.getAttribute('href')) { this.imageNum++; }
			}
		}
		
		// Now load up the appropriate viewer.
		this.changeContent(this.isLyteframe ? this.frameNum : (this.isSlideshow ? this.slideNum : this.imageNum));
		
	}; // start()

//***************************************************************************/
// changeContent() - Hide most elements and load iframe or preload image in 
//		preparation for resizing the content container.
//
//	@param	int		iContentNum
//***************************************************************************/
	Lytebox.prototype.changeContent = function(iContentNum) {
		
		// Store the number of the content (x of x) in a member property.
		this.contentNum = iContentNum;
		
		// Before we do anything, ensure that the overlay has been loaded. If it hasn't, wait 1/4th of a second and make this call again.
		if (!this.overlayLoaded) {
			this.changeContentTimerArray[this.changeContentTimerCount++] = setTimeout("$lb.changeContent(" + this.contentNum + ")", 250);
			return;
		} else {
			// Clear the timer for changeContent...
			for (var i = 0; i < this.changeContentTimerCount; i++) { window.clearTimeout(this.changeContentTimerArray[i]); }
		}
		
		// If we're not inheriting options, then set the options from the current link and use global defaults for values not specified.
		var sDataLyteOptions = (this.isLyteframe) ? this.frameArray[this.contentNum][3] : (this.isSlideshow ? this.slideArray[this.contentNum][3] : this.imageArray[this.contentNum][3]);
		if (!this.inherit || /inherit:false/i.test(sDataLyteOptions)) {
			this.setOptions(String(sDataLyteOptions));
			
		// If we're here then we're inheriting options.
		} else {
			// Store the options in the from the element in a set.
			var sDataLyteOptions1 = String((this.isLyteframe) ? this.frameArray[0][3] : (this.isSlideshow ? this.slideArray[0][3] : this.imageArray[0][3]));
			
			// How we'll determine if the width/height is changing (iframe content only).
			if (this.isLyteframe) {
				var sWidth = sHeight = null;
				
				// First we get the width and height, if they exist. If not then an exception is thrown which we catch.
				try { sWidth = sDataLyteOptions.match(/width:\d+(%|px|)/i)[0]; } catch(e) { }
				try { sHeight = sDataLyteOptions.match(/height:\d+(%|px|)/i)[0]; } catch(e) { }
				
				// If the width/height are changing, then we'll replace the width/height from the first element with those of the current element.
				if (!this.isEmpty(sWidth)) {
					sDataLyteOptions1 = sDataLyteOptions1.replace(/width:\d+(%|px|)/i, sWidth);
				}
				if (!this.isEmpty(sHeight)) {
					sDataLyteOptions1 = sDataLyteOptions1.replace(/height:\d+(%|px|)/i, sHeight);
				}
			}
			this.setOptions(sDataLyteOptions1);
		}
		
		// Show the Lytebox container.
		var object = this.doc.$('lbMain');
			object.style.display = '';
			
		// (07/20/2011) Viewer will stay in fixed position if scrolling up/down - fixed by A.Popov http://s3blog.org
		// (08/17/2011) If this.resizeImage == false, we have to allow scrolling as the viewer will likely expand past the viewable document/window area
		var iDivisor = 40;
		if (this.autoResize && this.fixedPosition) {
			// IE6, IE7, and IE8+ in QuirksMode do not properly handle fixed positioning of an element, so this is a workaround for that.
			if (this.ie && (this.ieVersion <= 7 || this.doc.compatMode == 'BackCompat')) {
				object.style.top = (this.getPageScroll() + (this.aPageSize[3] / iDivisor)) + "px";
				var ps = (this.aPageSize[3] / iDivisor);
				this.scrollHandler = function(){
					$lb.doc.$('lbMain').style.top = ($lb.getPageScroll() + ps) + 'px';
				}
				this.bodyOnscroll = document.body.onscroll;
				if (window.addEventListener) {
					window.addEventListener('scroll', this.scrollHandler);
				} else if (window.attachEvent) {
					window.attachEvent('onscroll', this.scrollHandler);
				}
				object.style.position = "absolute";
			} else {
				object.style.top = ((this.aPageSize[3] / iDivisor)) + "px";
				object.style.position = "fixed";
			}
		} else {
			object.style.position = "absolute";
			object.style.top = (this.getPageScroll() + (this.aPageSize[3] / iDivisor)) + "px";
		}
		
		// Remove the bottom padding, if any. Padding is added when we show the content, and removing it will effectively remove the
		// additional white space that is left over when we remove the description (lbDescBottom) container.
		this.doc.$('lbOuterContainer').style.paddingBottom = '0';
			
		// Reset the bottom border from #lbOuterContainer.
		if (!this.outerBorder) {
			this.doc.$('lbOuterContainer').style.border = 'none';
		} else {
			this.doc.$('lbOuterContainer').setAttribute(this.classAttribute, this.theme);
		}
			
		// Now we need to assign "onclick" handlers for various buttons. It's being done here because IE was apparently having
		// problems with assigning these when the elements were actually created on the page. This occurred randomly, and I was
		// not able to determine why. Whatever the case, the buttons appear to work fine by moving the assignments here. IE sux.
		// (07/20/2011) Added this.forceCloseClick, which disallows closing the viewer if user clicks outside of viewer.
		if (this.forceCloseClick) {
			this.doc.$('lbOverlay').onclick = '';
		} else {
			this.doc.$('lbOverlay').onclick = function() { $lb.end(); return false; }
		}
		this.doc.$('lbMain').onclick = function(e) {
			var e = e;
			if (!e) {
				if (window.parent.frames[window.name] && (parent.document.getElementsByTagName('frameset').length <= 0)) {
					e = window.parent.window.event;
				} else {
					e = window.event;
				}
			}
			var id = (e.target ? e.target.id : e.srcElement.id);
			if ((id == 'lbMain') && (!$lb.forceCloseClick)) { $lb.end(); return false; }
		}
		this.doc.$('lbPrintTop').onclick = this.doc.$('lbPrint').onclick = function() { $lb.printWindow(); return false; }
		this.doc.$('lbCloseTop').onclick = this.doc.$('lbClose').onclick = function() { $lb.end(); return false; }
		this.doc.$('lbPauseTop').onclick = function() { $lb.togglePlayPause("lbPauseTop", "lbPlayTop"); return false; }
		this.doc.$('lbPause').onclick = function() { $lb.togglePlayPause("lbPause", "lbPlay"); return false; }
		this.doc.$('lbPlayTop').onclick = function() { $lb.togglePlayPause("lbPlayTop", "lbPauseTop"); return false; }
		this.doc.$('lbPlay').onclick = function() { $lb.togglePlayPause("lbPlay", "lbPause"); return false; }
		
		// We need to be sure that the "Play" button is visible if the slideshow is paused, since "Pause" is displayed by default.
		if (this.isSlideshow && this.showPlayPause && this.isPaused) {
			this.doc.$('lbPlay').style.display = '';
			this.doc.$('lbPause').style.display = 'none';
		}
		
		// We clear the slideshow timers, if it's a slideshow, just in case "Next/Prev" navigation is enabled and user moves back/forward.
		if (this.isSlideshow) {
			for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
		}
		
		// Reset the bottom border from #lbOuterContainer.
		if (!this.outerBorder) {
			this.doc.$('lbOuterContainer').style.border = 'none';
		} else {
			this.doc.$('lbOuterContainer').setAttribute(this.classAttribute, this.theme);
		}
		
		// This will be used to reposition the "lbLoading" container (or decrease the top margin, rather) if showing the "lbTopContainer".
		// The default is 10 pixels which accounts for the existing top margin.
		var iDecreaseMargin = 10;
	
		// hide elements during transition
		if (this.titleTop || this.navTop) {
			this.doc.$('lbTopContainer').style.visibility = 'hidden';
			iDecreaseMargin += this.doc.$('lbTopContainer').offsetHeight;
		} else {
			this.doc.$('lbTopContainer').style.display = 'none';
		}
		this.doc.$('lbBottomContainer').style.display = 'none';
		this.doc.$('lbImage').style.display = 'none';
		this.doc.$('lbIframe').style.display = 'none';
		this.doc.$('lbPrevHov').style.display = 'none';
		this.doc.$('lbNextHov').style.display =  'none';
		this.doc.$('lbIframeContainer').style.display = 'none';
		
		// Now show the loading graphic.
		this.doc.$('lbLoading').style.marginTop = '-' + iDecreaseMargin + 'px';
		this.doc.$('lbLoading').style.display = '';
		
		if (this.isLyteframe) {
			var iframe = $lb.doc.$('lbIframe');
				iframe.src = 'about:blank';
			
			// (07/20/2011) if width/height are percentages, determine width in pixels before setting - fixed by A.Popov http://s3blog.org
			var w = this.trim(this.width);
			var h = this.trim(this.height);
			if (/\%/.test(w)) {
				var percent = parseInt(w);
				w = parseInt((this.aPageSize[2]-50)*percent/100);
				w = w+'px';
			}
			if (/\%/.test(h)) {
				var percent = parseInt(h);
				h = parseInt((this.aPageSize[3]-150)*percent/100);
				h = h+'px';
			}
			
			// If autoResize is enabled, then ensure we don't resize out of the page boundaries.
			if (this.autoResize) {
				var x = this.aPageSize[2] - 50;
				var y = this.aPageSize[3] - 150;
				w = (parseInt(w) > x ? x : w) + 'px';
				h = (parseInt(h) > y ? y : h) + 'px';
			}
			
			iframe.height = this.height = h;
			iframe.width = this.width = w;
			iframe.scrolling = this.scrolling;
			
			// Now set some basic styles, noting that we get a proper reference to the document.
			var oDoc = iframe.contentWindow || iframe.contentDocument;
			
			// Wrap in a try/catch to avoid permission errors.
			try {
				if (oDoc.document) {
					oDoc = oDoc.document;
				}
				oDoc.body.style.margin = 0;
				oDoc.body.style.padding = 0;
				
				// Set up scrolling for IE6/7/8 (doesn't always work).
				if (this.ie && this.ieVersion <= 8) {
					oDoc.body.scroll = this.scrolling;
					oDoc.body.overflow = this.scrolling = 'no' ? 'hidden' : 'auto';
				}
			} catch(e) { }
			
			// Now resize the container. Once it's done resizing, noting that after resize this.loadContent() is called.
			this.resizeContainer(parseInt(this.width), parseInt(this.height));
			
		} else {
			this.imgPreloader = new Image();
		
			// once image is preloaded, resize image container (and image, if necessary)
			this.imgPreloader.onload = function() {
				
				var imageWidth = $lb.imgPreloader.width;
				var imageHeight = $lb.imgPreloader.height;
				
				if ($lb.autoResize) {
					var x = $lb.aPageSize[2] - 50;
					var y = $lb.aPageSize[3] - 150;
					
					if (imageWidth > x) {
						imageHeight = Math.round(imageHeight * (x / imageWidth));
						imageWidth = x; 
						if (imageHeight > y) { 
							imageWidth = Math.round(imageWidth * (y / imageHeight));
							imageHeight = y; 
						}
					} else if (imageHeight > y) { 
						imageWidth = Math.round(imageWidth * (y / imageHeight));
						imageHeight = y; 
						if (imageWidth > x) {
							imageHeight = Math.round(imageHeight * (x / imageWidth));
							imageWidth = x;
						}
					}
				}
				
				var lbImage = $lb.doc.$('lbImage');
				lbImage.src = $lb.imgPreloader.src; // TODO: ($lb.isSlideshow ? $lb.slideArray[$lb.contentNum][0] : $lb.imageArray[$lb.contentNum][0]);
				lbImage.width = imageWidth;
				lbImage.height = imageHeight;
				$lb.resizeContainer(imageWidth, imageHeight);
				$lb.imgPreloader.onload = function() {}; // IE animated .gif fix.
			}
		
			this.imgPreloader.src = (this.isSlideshow ? this.slideArray[this.contentNum][0] : this.imageArray[this.contentNum][0]);
		}
		
	}; // changeContent()

//******************************************************************************/
// resizeContainer() - Resizes the container based on the dimensions provided.
//
//	@param	int		iWidth
//	@param	int		iHeight
//******************************************************************************/
	Lytebox.prototype.resizeContainer = function(iWidth, iHeight) {
		
		// store these values in member properties
		this.resizeWidth = iWidth;
		this.resizeHeight = iHeight;
	
		// get current height and width
		this.wCur = this.doc.$('lbOuterContainer').offsetWidth;
		this.hCur = this.doc.$('lbOuterContainer').offsetHeight;
		
		// scalars based on change from old to new
		this.xScale = ((this.resizeWidth  + (this.borderSize * 2)) / this.wCur) * 100;
		this.yScale = ((this.resizeHeight  + (this.borderSize * 2)) / this.hCur) * 100;
	
		// calculate size difference between new and old image, and resize if necessary
		var wDiff = (this.wCur - this.borderSize * 2) - this.resizeWidth;
		var hDiff = (this.hCur - this.borderSize * 2) - this.resizeHeight;
		
		// Determine if the width needs to be resized.
		this.wDone = (wDiff == 0);
		
		// If the container needs to be resized, then call resizeH() to take care of business. Note that resizeH() will call resizeW() when done if need be.
		if (!(hDiff == 0)) {
			this.hDone = false;
			this.resizeH('lbOuterContainer', this.hCur, this.resizeHeight + this.borderSize * 2, this.getPixelRate(this.hCur, this.resizeHeight));
		} else {
			this.hDone = true;
			
			// If the container needs to be resized (horizontally), make it so.
			if (!this.wDone) {
				this.resizeW('lbOuterContainer', this.wCur, this.resizeWidth + this.borderSize * 2, this.getPixelRate(this.wCur, this.resizeWidth));
			}
		}
	
		// if new and old image are same size and no scaling transition is necessary, do a quick pause to prevent image flicker.
		if ((hDiff == 0) && (wDiff == 0)) {
			if (this.ie){ this.pause(250); } else { this.pause(100); } 
		}
		
		// Set the correct height for the hover containers (prev/next).
		this.doc.$('lbPrevHov').style.height = this.resizeHeight + "px";
		this.doc.$('lbNextHov').style.height = this.resizeHeight + "px";
		
		// We should make a call to this function here only if this.hDone and this.wDone are true. Otherwise, we'll call from this.resizeW().
		if (this.hDone && this.wDone) {
			if (this.isLyteframe) {
				this.loadContent();
			} else {
				this.showContent();
			}
		}
		
	}; // resizeContainer()
	
//******************************************************************************/
// loadContent() - Loads iframe content after the container has been resized. Once
//		the contents of the iframe are loaded, showContent(0 is then called.
//******************************************************************************/
	Lytebox.prototype.loadContent = function() {
		
		// try/catch added for IE6
		try {
			
			// Get a reference to our iframe object.
			var iframe = this.doc.$('lbIframe');
			
			// (07/20/2011) identifier for cgi.script-server - by A.Popov http://s3blog.org
			var uri = this.frameArray[this.contentNum][0];
			if (!this.inline && this.appendQS) {
				uri += ((/\?/.test(uri)) ? '&' : '?') + 'request_from=lytebox';
			}
			
			// Handle YouTube videos properly. If autoPlay is turned on (default) then append "autoplay=1" to the URI.
			if (this.autoPlay && /youtube/i.test(uri)) {
				uri += ((/\?/.test(uri)) ? '&' : '?') + 'autoplay=1';
			}
			
			// Firefox (in general) does not always fire the "onload" event when loading ActiveX (PDF, WMV, etc.), so we go ahead 
			// and call showContent() immediately.
			// Additionally, various browsers crash when attempting to play embedded WMV files, so we disable auto-embedding 
			// by default. Browsers that don't know to do with this format will offer up a "download" or "open with" dialog, 
			// which is much better than the alternative.
			if (!this.autoEmbed || (this.ff && (uri.match(/.pdf|.mov|.wmv/i)))) {
				this.frameSource = uri;
				this.showContent();
				return;
			}
			
			// Assign handlers to determine when the iframe has loaded, and call showContent() once loaded.
			if (this.ie) {
				iframe.onreadystatechange = function() {
					if ($lb.doc.$('lbIframe').readyState == "complete") {
						$lb.showContent();
						$lb.doc.$('lbIframe').onreadystatechange = null;
					}
				};
			} else {
				iframe.onload = function() {
					$lb.showContent();
					$lb.doc.$('lbIframe').onload = null;
				};
			}
			
			// Now determine if we need to embed the content within the iFrame (either inline content or media file).
			// We'll do that by check the extension (mov, avi, mpg, mpeg, wmv, swf)
			if (this.inline || (uri.match(/.mov|.avi|.wmv|.mpg|.mpeg|.swf/i))) {
				
				// Reset the iframe source so that the previous page (cached) doesn't load.
				iframe.src = 'about:blank';
				this.frameSource = '';
				
				// Builds the iFrame window + styles + contents...
				var sHtml = (this.inline) ? this.doc.$(uri.substr(uri.indexOf('#') + 1, uri.length)).innerHTML : this.buildObject(parseInt(this.width), parseInt(this.height), uri);
				
				// Get a proper reference to the document.
				var oDoc = iframe.contentWindow || iframe.contentDocument;
				if (oDoc.document) {
					oDoc = oDoc.document;
				}
				
				// Now write to the document and set some basic styles.
				oDoc.open();
				oDoc.write(sHtml);
				oDoc.close();
				oDoc.body.style.margin = 0;
				oDoc.body.style.padding = 0;
					
				// Set these if it's not inline content.
				if (!this.inline) {
					oDoc.body.style.backgroundColor = '#fff';
					oDoc.body.style.fontFamily = 'Verdana, Helvetica, sans-serif';
					oDoc.body.style.fontSize = '0.9em';
				}
				
				// Set this to blank so that IE doesn't refresh later on.
				this.frameSource = '';
				
			// Otherwise, just load the passed in URL.
			} else {
				
				// Various browsers seem to have issues loading Google maps (with the marker being in the proper position),
				// so we'll store the URL in a property so that we can reload the window later.
				this.frameSource = uri;
				iframe.src = uri;
			}

		} catch(e) { }
		
	}; // loadContent()

//******************************************************************************/
// showContent() - Display content (iframes, images, etc.) and begin preloading 
//		neighbors images, if we are dealing with images.
//******************************************************************************/
	Lytebox.prototype.showContent = function() {
			
		// Show the appropriate buttons.
		if (this.isSlideshow) {
			// if last image in set, either loop (if option is set) or close the slideshow viewer
			if(this.contentNum == (this.slideArray.length - 1)) {
				if (this.loopSlideshow) {
					this.slideshowIDArray[this.slideshowIDCount++] = setTimeout("$lb.changeContent(0)", this.slideInterval);
				} else if (this.autoEnd) {
					this.slideshowIDArray[this.slideshowIDCount++] = setTimeout("$lb.end('slideshow')", this.slideInterval);
				}
			// otherwise, call changeContent in "this.slideInterval" seconds to automatically move to the next slide
			} else {
				if (!this.isPaused) {
					this.slideshowIDArray[this.slideshowIDCount++] = setTimeout("$lb.changeContent("+(this.contentNum+1)+")", this.slideInterval);
				}
			}
			this.doc.$('lbHoverNav').style.display = (this.ieVersion != 6 && this.showNavigation && this.navTypeHash['Hover_by_type_' + this.navType] ? '' : 'none');
			this.doc.$('lbCloseTop').style.display = (this.showClose && this.navTop ? '' : 'none');
			this.doc.$('lbClose').style.display = (this.showClose && !this.navTop ? '' : 'none');
			this.doc.$('lbBottomData').style.display = (this.showDetails ? '' : 'none');
			this.doc.$('lbPauseTop').style.display = (this.showPlayPause && this.navTop ? (!this.isPaused ? '' : 'none') : 'none');
			this.doc.$('lbPause').style.display = (this.showPlayPause && !this.navTop ? (!this.isPaused ? '' : 'none') : 'none');
			this.doc.$('lbPlayTop').style.display = (this.showPlayPause && this.navTop ? (!this.isPaused ? 'none' : '') : 'none');
			this.doc.$('lbPlay').style.display = (this.showPlayPause && !this.navTop ? (!this.isPaused ? 'none' : '') : 'none');
			this.doc.$('lbPrevTop').style.display = (this.navTop && this.showNavigation && this.navTypeHash['Display_by_type_' + this.navType] ? '' : 'none');
			this.doc.$('lbPrev').style.display = (!this.navTop && this.showNavigation && this.navTypeHash['Display_by_type_' + this.navType] ? '' : 'none');
			this.doc.$('lbNextTop').style.display = (this.navTop && this.showNavigation && this.navTypeHash['Display_by_type_' + this.navType] ? '' : 'none');
			this.doc.$('lbNext').style.display = (!this.navTop && this.showNavigation && this.navTypeHash['Display_by_type_' + this.navType] ? '' : 'none');

		} else {
			this.doc.$('lbHoverNav').style.display = (this.ieVersion != 6 && this.navTypeHash['Hover_by_type_' + this.navType] && !this.isLyteframe ? '' : 'none');
			if ((this.navTypeHash['Display_by_type_' + this.navType] && !this.isLyteframe && this.imageArray.length > 1) || (this.frameArray.length > 1 && this.isLyteframe)) {
				this.doc.$('lbPrevTop').style.display = (this.navTop ? '' : 'none');
				this.doc.$('lbPrev').style.display = (!this.navTop ? '' : 'none');
				this.doc.$('lbNextTop').style.display = (this.navTop ? '' : 'none');
				this.doc.$('lbNext').style.display = (!this.navTop ? '' : 'none');
			} else {
				this.doc.$('lbPrevTop').style.display = 'none';
				this.doc.$('lbPrev').style.display = 'none';
				this.doc.$('lbNextTop').style.display = 'none';
				this.doc.$('lbNext').style.display = 'none';
			}
			this.doc.$('lbCloseTop').style.display = (this.navTop ? '' : 'none');
			this.doc.$('lbClose').style.display = (!this.navTop ? '' : 'none');				
			this.doc.$('lbBottomData').style.display = '';
			this.doc.$('lbPauseTop').style.display = 'none';
			this.doc.$('lbPause').style.display = 'none';
			this.doc.$('lbPlayTop').style.display = 'none';
			this.doc.$('lbPlay').style.display = 'none';
		}
		
		// Show the print buttons, if necessary.
		this.doc.$('lbPrintTop').style.display = (this.showPrint && this.navTop ? '' : 'none');
		this.doc.$('lbPrint').style.display = (this.showPrint && !this.navTop ? '' : 'none');
		
		// Update the details containers (title, description, etc.) before showing the image/iframe containers.
		// This needs to be done here so that the image/iframe don't resize outside the bottom container.
		this.updateDetails();
		
		// Hide the loading graphic and show the appropriate content container.
		this.doc.$('lbLoading').style.display = 'none';
		this.doc.$('lbImageContainer').style.display = (this.isLyteframe ? 'none' : '');
		this.doc.$('lbIframeContainer').style.display = (this.isLyteframe ? '' : 'none');
		
		if (this.isLyteframe) {
			// Certain sites, such as Google Maps, don't load properly in IE, so we have to reload them. Retarded, I know.
			// If this is the case then this.frameSource will be set with the appropriate URL.
			if (!this.isEmpty(this.frameSource)) {
				this.doc.$('lbIframe').src = this.frameSource;
			}
			this.doc.$('lbIframe').style.display = '';
			this.fadeIn({ id: 'lbIframe', opacity: (this.doAnimations && (!this.ie || this.ieVersion >= 9) ? 0 : 100) });
		} else {
			this.doc.$('lbImage').style.display = '';
			this.fadeIn({ id: 'lbImage', opacity: (this.doAnimations && (!this.ie || this.ieVersion >= 9) ? 0 : 100) });
			this.preloadNeighborImages();
		}
		
		// Finally, see if we have a "after start" handler set and make the call if so.
		if (!this.isEmpty(this.afterStart)) {
			var callback = window[this.afterStart];
			if (typeof callback === 'function') {
				callback(this.args);
			}
		}
		
	}; // showContent()

//******************************************************/
// updateDetails() - Display caption/title and nav.
//******************************************************/
	Lytebox.prototype.updateDetails = function() {
		
		// Get the title contents from the appropriate array.
		var sTitle = (this.isSlideshow ? this.slideArray[this.contentNum][1] : (this.isLyteframe ? this.frameArray[this.contentNum][1] : this.imageArray[this.contentNum][1]));
		var sDesc  = (this.isSlideshow ? this.slideArray[this.contentNum][2] : (this.isLyteframe ? this.frameArray[this.contentNum][2] : this.imageArray[this.contentNum][2]));
		
		// IE will render block level elements (add space) even if there is no content, so we'll show/hide the title
		// and description containers if there is no content to display.
		if (this.ie && this.ieVersion <= 7 || (this.ieVersion >= 8 && this.doc.compatMode == 'BackCompat')) {
			this.doc.$(this.titleTop ? 'lbTitleBottom' : 'lbTitleTop').style.display = 'none';
			this.doc.$(this.titleTop ? 'lbTitleTop' : 'lbTitleBottom').style.display = (this.isEmpty(sTitle) ? 'none' : 'block');
		}
		this.doc.$('lbDescBottom').style.display = (this.isEmpty(sDesc) ? 'none' : '');
		
		// Determine which container to write the title to.
		this.doc.$(this.titleTop ? 'lbTitleTop' : 'lbTitleBottom').innerHTML = (this.isEmpty(sTitle) ? '' : sTitle);
		this.doc.$(this.titleTop ? 'lbTitleBottom' : 'lbTitleTop').innerHTML = '';
		this.doc.$(this.titleTop ? 'lbNumBottom' : 'lbNumTop').innerHTML = '';
		
		// Update the navigation, which will show/hide the appropriate buttons.
		this.updateNav();
		
		// Show/hide the top container as necessary.
		if (this.titleTop || this.navTop) {
			this.doc.$('lbTopContainer').style.display = 'block';
			this.doc.$('lbTopContainer').style.visibility = 'visible';
		} else {
			this.doc.$('lbTopContainer').style.display = 'none';
		}
		
		// Now we'll get the number/page display container (x of x)
		var object = (this.titleTop ? this.doc.$('lbNumTop') : this.doc.$('lbNumBottom'));
		
		// if part of set display 'Image x of x' or 'Page x of x'
		if (this.isSlideshow && this.slideArray.length > 1) {
			object.innerHTML = this.label['image'].replace('%1', this.contentNum + 1).replace('%2', this.slideArray.length);
		} else if (this.imageArray.length > 1 && !this.isLyteframe) {
			object.innerHTML = this.label['image'].replace('%1', this.contentNum + 1).replace('%2', this.imageArray.length);
		} else if (this.frameArray.length > 1 && this.isLyteframe) {
			object.innerHTML = this.label['page'].replace('%1', this.contentNum + 1).replace('%2', this.frameArray.length);
		} else {
			object.innerHTML = '';
		}
		
		// If we have a description then add it to the appropriate container.
		var bAddSpacer = !(this.titleTop || (this.isEmpty(sTitle) && this.isEmpty(object.innerHTML)));
		this.doc.$('lbDescBottom').innerHTML = (this.isEmpty(sDesc) ? '' : (bAddSpacer ? '<br style="line-height:0.6em;" />' : '') + sDesc);
		
		// For IE (certain versions) we have to explicitely set the size of the lbBottomNav container so that it doesn't try to consume
		// 100% of the width. Each button element amounts to 39px (26 width + 13 margin/padding).
		var iNavWidth = 0;
		if (this.ie && this.ieVersion <= 7 || (this.ieVersion >= 8 && this.doc.compatMode == 'BackCompat')) {
			// Start with the simple buttons (print, play/pause).
			iNavWidth = 39 + (this.showPrint ? 39 : 0) + (this.isSlideshow && this.showPlayPause ? 39 : 0);
			
			// Now determine if the next and/or prev buttons are being shown.
			if ((this.isSlideshow && this.slideArray.length > 1 && this.showNavigation && this.navType != 1) ||
				(this.frameArray.length > 1 && this.isLyteframe) ||
				(this.imageArray.length > 1 && !this.isLyteframe && this.navType != 1)) {
					iNavWidth += 39*2;
			}
		}
		
		// If there is nothing to show, then we don't really need to display the bottom container.
		this.doc.$('lbBottomContainer').style.display = (!(this.titleTop && this.navTop) || !this.isEmpty(sDesc) ? 'block' : 'none');
		
		// Now set the correct width for the data container so that it doesn't cause the right (nav) container to drop down to the next line.
		if (this.titleTop && this.navTop) {
			if (iNavWidth > 0) {
				this.doc.$('lbTopNav').style.width = iNavWidth + 'px';
			}
			this.doc.$('lbTopData').style.width = (this.doc.$('lbTopContainer').offsetWidth - this.doc.$('lbTopNav').offsetWidth - 15) + 'px';
			if (!this.isEmpty(sDesc)) {
				this.doc.$('lbDescBottom').style.width = (this.doc.$('lbBottomContainer').offsetWidth - 15) + 'px';
			}
		} else if ((!this.titleTop || !this.isEmpty(sDesc)) && !this.navTop) {
			if (iNavWidth > 0) {
				this.doc.$('lbBottomNav').style.width = iNavWidth + 'px';
			}
			this.doc.$('lbBottomData').style.width = (this.doc.$('lbBottomContainer').offsetWidth - this.doc.$('lbBottomNav').offsetWidth - 15) + 'px';
			this.doc.$('lbDescBottom').style.width = this.doc.$('lbBottomData').style.width;
		}
		
		// Add the appropriate padding to the bottom of the container, if necessary (decision made in function).
		this.fixBottomPadding();
		
		// TODO: At some point we should determine if we can calculate the dimensions of the top/bottom container before resizing 
		//		 the Lytebox container so that we don't have to deal with the below issue. That's going to be tricky, so for now
		//		 we can work around that by simply enabling page scrolling if the viewer is larger than the page dimensions.
		this.aPageSize = this.getPageSize();
		
		// IE does not return the correct value for getPageScroll, so the 2nd time around page scrolling won't be disabled.
		var iMainTop = parseInt(this.doc.$('lbMain').style.top);
		if ((this.ie && this.ieVersion <= 7) || (this.ieVersion >= 8 && this.doc.compatMode == 'BackCompat')) {
			iMainTop = (this.ie ? parseInt(this.doc.$('lbMain').style.top) - this.getPageScroll() : parseInt(this.doc.$('lbMain').style.top));
		}
		var iOverlap = (this.doc.$('lbOuterContainer').offsetHeight + iMainTop) - this.aPageSize[3];
		var iDivisor = 40;
		
		if (iOverlap > 0 && this.autoResize && this.fixedPosition) {
			// IE6 (any mode) and IE7+ in QuirksMode do not properly handle fixed positioning of an element, so this is a workaround for that.
			if (this.ie && (this.ieVersion <= 7 || this.doc.compatMode == 'BackCompat')) {
				document.body.onscroll = this.bodyOnscroll;
				if (window.removeEventListener) {
					window.removeEventListener('scroll', this.scrollHandler);
				} else if (window.detachEvent) {
					window.detachEvent('onscroll', this.scrollHandler);
				}
			}
			this.doc.$('lbMain').style.position = "absolute";
			this.doc.$('lbMain').style.top = (this.getPageScroll() + (this.aPageSize[3] / iDivisor)) + "px";
		}
		
	}; // updateDetails()

//***********************************************************************/
// updateNav() - Display appropriate previous and next hover navigation.
//***********************************************************************/
	Lytebox.prototype.updateNav = function() {
		
		// Slideshow Viewer
		if (this.isSlideshow) {
			// if not first image in set, display prev image button
			if (this.contentNum != 0) {
				if (this.navTypeHash['Display_by_type_' + this.navType] && this.showNavigation) {
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').setAttribute(this.classAttribute, this.theme);
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').style.display = '';
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').onclick = function() {
						if ($lb.pauseOnPrevClick) { $lb.togglePlayPause($lb.navTop ? 'lbPauseTop' : 'lbPause', $lb.navTop ? 'lbPlayTop' : 'lbPlay'); }
						$lb.changeContent($lb.contentNum - 1); return false;
					}
				}
				if (this.navTypeHash['Hover_by_type_' + this.navType]) {
					var object = this.doc.$('lbPrevHov');
					object.style.display = '';
					object.onclick = function() {
						if ($lb.pauseOnPrevClick) { $lb.togglePlayPause($lb.navTop ? 'lbPauseTop' : 'lbPause', $lb.navTop ? 'lbPlayTop' : 'lbPlay'); }
						$lb.changeContent($lb.contentNum - 1); return false;
					}
				}
			} else {
				if (this.navTypeHash['Display_by_type_' + this.navType]) {
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').setAttribute(this.classAttribute, this.theme + 'Off');
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').onclick = function() { return false; }
				}
			}
			
			// if not last image in set, display next image button
			if (this.contentNum != (this.slideArray.length - 1) && this.showNavigation) {
				if (this.navTypeHash['Display_by_type_' + this.navType]) {
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').setAttribute(this.classAttribute, this.theme);
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').style.display = '';
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').onclick = function() {
						if ($lb.pauseOnNextClick) { $lb.togglePlayPause($lb.navTop ? 'lbPauseTop' : 'lbPause', $lb.navTop ? 'lbPlayTop' : 'lbPlay'); }
						$lb.changeContent($lb.contentNum + 1); return false;
					}
				}
				if (this.navTypeHash['Hover_by_type_' + this.navType]) {
					var object = this.doc.$('lbNextHov');
					object.style.display = '';
					object.onclick = function() {
						if ($lb.pauseOnNextClick) { $lb.togglePlayPause($lb.navTop ? 'lbPauseTop' : 'lbPause', $lb.navTop ? 'lbPlayTop' : 'lbPlay'); }
						$lb.changeContent($lb.contentNum + 1); return false;
					}
				}
			} else {
				if (this.navTypeHash['Display_by_type_' + this.navType]) { 
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').setAttribute(this.classAttribute, this.theme + 'Off');
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').onclick = function() { return false; }
				}
			}
		
		// HTML/Embedded Media Viewer
		} else if (this.isLyteframe) {
			// if not first image in set, display prev image button
			if(this.contentNum != 0) {
				this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').setAttribute(this.classAttribute, this.theme);
				this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').style.display = '';
				this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').onclick = function() {
					$lb.changeContent($lb.contentNum - 1); return false;
				}
			} else {
				this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').setAttribute(this.classAttribute, this.theme + 'Off');
				this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').onclick = function() { return false; }
			}
			
			// if not last image in set, display next image button
			if(this.contentNum != (this.frameArray.length - 1)) {
				this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').setAttribute(this.classAttribute, this.theme);
				this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').style.display = '';
				this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').onclick = function() {
					$lb.changeContent($lb.contentNum + 1); return false;
				}
			} else {
				this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').setAttribute(this.classAttribute, this.theme + 'Off');
				this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').onclick = function() { return false; }
			}
			
		// Image Viewer (Lytebox)	
		} else {
			// if not first image in set, display prev image button
			if(this.contentNum != 0) {
				if (this.navTypeHash['Display_by_type_' + this.navType]) {
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').setAttribute(this.classAttribute, this.theme);
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').style.display = '';
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').onclick = function() {
						$lb.changeContent($lb.contentNum - 1); return false;
					}
				}
				if (this.navTypeHash['Hover_by_type_' + this.navType]) {
					var object2 = this.doc.$('lbPrevHov');
					object2.style.display = '';
					object2.onclick = function() {
						$lb.changeContent($lb.contentNum - 1); return false;
					}
				}
			} else {
				if (this.navTypeHash['Display_by_type_' + this.navType]) {
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').setAttribute(this.classAttribute, this.theme + 'Off');
					this.doc.$(this.navTop ? 'lbPrevTop' : 'lbPrev').onclick = function() { return false; }
				}
			}
			
			// if not last image in set, display next image button
			if(this.contentNum != (this.imageArray.length - 1)) {
				if (this.navTypeHash['Display_by_type_' + this.navType]) {
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').setAttribute(this.classAttribute, this.theme);
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').style.display = '';
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').onclick = function() {
						$lb.changeContent($lb.contentNum + 1); return false;
					}
				}
				if (this.navTypeHash['Hover_by_type_' + this.navType]) {
					var object2 = this.doc.$('lbNextHov');
					object2.style.display = '';
					object2.onclick = function() {
						$lb.changeContent($lb.contentNum + 1); return false;
					}
				}
			} else {
				if (this.navTypeHash['Display_by_type_' + this.navType]) { 
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').setAttribute(this.classAttribute, this.theme + 'Off');
					this.doc.$(this.navTop ? 'lbNextTop' : 'lbNext').onclick = function() { return false; }
				}
			}
		}
		
		this.enableKeyboardNav();
		
	}; // updateNav()
	
//********************************************************************************************************************/
// fixBottomPadding() - Adds padding to the bottom of the outer container so that it encompasses the details container.
//********************************************************************************************************************/
	Lytebox.prototype.fixBottomPadding = function() {
		
		// Note that we exclude >=IE7 running in quirksmode and IE6 as they apparently resize the container properly.
		if (!((this.ieVersion == 7 || this.ieVersion == 8 || this.ieVersion == 9) && this.doc.compatMode == 'BackCompat') && this.ieVersion != 6) {
			// We need to get the width and height of lbTopContainer and lbBottomContainer, then increase the bottom padding.
			var titleHeight = this.doc.$('lbTopContainer').offsetHeight + 5; // 5 is added for the additional top margin given in the CSS
			var offsetHeight = (titleHeight == 5 ? 0 : titleHeight) + this.doc.$('lbBottomContainer').offsetHeight;
			
			// TODO: Need to figure out why the outer container is not properly resizing instead of this hacky solution. As of right now this seems to fix it.
			this.doc.$('lbOuterContainer').style.paddingBottom = (offsetHeight + 5) + 'px';
		}
		
	}; // fixBottomPadding()

//*********************************************************************************/
// enableKeyboardNav(), disableKeyboardNav(), keyboardAction() -- COMBINED COMMENT
//*********************************************************************************/
	Lytebox.prototype.enableKeyboardNav = function() { document.onkeydown = this.keyboardAction; };
	Lytebox.prototype.disableKeyboardNav = function() { document.onkeydown = ''; };
	Lytebox.prototype.keyboardAction = function(e) {
		var keycode = key = escape = null;
		keycode	= (e == null) ? event.keyCode : e.which;
		key		= String.fromCharCode(keycode).toLowerCase();
		escape  = (e == null) ? 27 : e.DOM_VK_ESCAPE;
		
		if ((key == 'x') || (key == 'c') || (keycode == escape || keycode == 27)) {	// close lytebox
			parent.$lb.end();
		} else if (keycode == 32 && $lb.isSlideshow && $lb.showPlayPause) { // toggle play/pause
			if ($lb.isPaused) {
				$lb.togglePlayPause($lb.navTop ? 'lbPlayTop' : 'lbPlay', $lb.navTop ? 'lbPauseTop' : 'lbPause');
			} else {
				$lb.togglePlayPause($lb.navTop ? 'lbPauseTop' : 'lbPause', $lb.navTop ? 'lbPlayTop' : 'lbPlay');
			}
			return false;
		} else if (key == 'p' || keycode == 37) {	// display previous image
			if ($lb.isSlideshow) {
				if($lb.contentNum != 0) {
					$lb.disableKeyboardNav();
					$lb.changeContent($lb.contentNum - 1);
				}
			} else if ($lb.isLyteframe) {
				if($lb.contentNum != 0) {
					$lb.disableKeyboardNav();
					$lb.changeContent($lb.contentNum - 1);
				}
			} else {
				if($lb.contentNum != 0) {
					$lb.disableKeyboardNav();
					$lb.changeContent($lb.contentNum - 1);
				}
			}
		} else if (key == 'n' || keycode == 39) {	// display next image
			if ($lb.isSlideshow) {
				if($lb.contentNum != ($lb.slideArray.length - 1)) {
					$lb.disableKeyboardNav();
					$lb.changeContent($lb.contentNum + 1);
				}
			} else if ($lb.isLyteframe) {
				if($lb.contentNum != ($lb.frameArray.length - 1)) {
					$lb.disableKeyboardNav();
					$lb.changeContent($lb.contentNum + 1);
				}
			} else {
				if($lb.contentNum != ($lb.imageArray.length - 1)) {
					$lb.disableKeyboardNav();
					$lb.changeContent($lb.contentNum + 1);
				}
			}
		}
	};

//************************************************************/
// preloadNeighborImages() - Preload previous and next images.
//************************************************************/
	Lytebox.prototype.preloadNeighborImages = function() {
		
		if (this.isSlideshow) {
			if ((this.slideArray.length - 1) > this.contentNum) {
				var preloadNextImage = new Image();
					preloadNextImage.src = this.slideArray[this.contentNum + 1][0];
			}
			if (this.contentNum > 0) {
				var preloadPrevImage = new Image();
					preloadPrevImage.src = this.slideArray[this.contentNum - 1][0];
			}
		} else {
			if ((this.imageArray.length - 1) > this.contentNum) {
				var preloadNextImage = new Image();
					preloadNextImage.src = this.imageArray[this.contentNum + 1][0];
			}
			if (this.contentNum > 0) {
				var preloadPrevImage = new Image();
					preloadPrevImage.src = this.imageArray[this.contentNum - 1][0];
			}
		}
		
	}; // preloadNeighborImages()

//************************************************************************************************/
// togglePlayPause() - Toggle play/pause buttons, and set isPaused to play or  pause the slideshow.
//
//	@param	string		sHideId		The ID of the element to hide
//	@param	string		sShowId		The ID of the element to show
//************************************************************************************************/
	Lytebox.prototype.togglePlayPause = function(sHideId, sShowId) {
		
		// clear all settimeout's immediately to avoid going to next slide... if "pause" was pressed.
		// if this was a slideshow, then clear the appropriate timeout id array
		if (this.isSlideshow && (sHideId == 'lbPauseTop' || sHideId == 'lbPause')) {
			for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
		}
		this.doc.$(sHideId).style.display = 'none';
		this.doc.$(sShowId).style.display = '';
		
		// determine the state (paused or not) and take the appropriate action (changeContent or end) if the play button is clicked
		if (sHideId == 'lbPlayTop' || sHideId == 'lbPlay') {
			this.isPaused = false;
			
			// if we're at the end, either loop or close the viewer depending on preferences
			if (this.contentNum == (this.slideArray.length - 1)) {
				if (this.loopSlideshow) {
					this.changeContent(0); // start over
				} else if (this.autoEnd) {
					this.end(); // close
				}
			} else {
				// change to the next image in the slideshow
				this.changeContent(this.contentNum + 1);
			}
		} else {
			this.isPaused = true;
		}
		
	}; // togglePlayPause()

//****************************************************************************/
// end() - sCaller was added as a parameter so that we could close Lytebox if
//		   the "Close" button is clicked while in the "paused" state.
//
//	@param	string	sCaller
//****************************************************************************/
	Lytebox.prototype.end = function(sCaller) {
		
		// If we're doing a slideshow and in a paused state, just return
		var closeClick = (sCaller == 'slideshow' ? false : true);
		if (this.isSlideshow && this.isPaused && !closeClick) { return; }
		
		// If we're here, then a request was made to close the viewer, so we'll first see if a "before close" handler is set.
		if (!this.isEmpty(this.beforeEnd)) {
			// Verify that this is a valid function, and if so call it. If the return value equates to false the viewer will remain open.
			var callback = window[this.beforeEnd];
			if (typeof callback === 'function') {
				if (!callback(this.args)) { return; }
			}
		}
		
		// Disable keyboard navigation while we clean up.
		this.disableKeyboardNav();
	
		// (07/20/2011) Save last func for body.onscroll - fixed by A.Popov http://s3blog.org
		document.body.onscroll = this.bodyOnscroll;
	
		// (07/20/2011) Refresh main page? - by A.Popov http://s3blog.org
		if (this.refreshPage) {
			var uri_href = top.location.href;
			var reg=/\#.*$/g;
			uri_href=uri_href.replace(reg, "");
			top.location.href = uri_href;
			return;
		}
		this.doc.$('lbMain').style.display = 'none';
		this.fadeOut({ id: 'lbOverlay', opacity: (this.doAnimations && this.animateOverlay && (!this.ie || this.ieVersion >= 9) ? this.maxOpacity : 0), speed: 5, display: 'none' });
		
		// Show <select> and <object> elements as needed, then reset the initial size of the outer container.
		this.toggleSelects('visible');
		if (this.hideObjects) { this.toggleObjects('visible'); }
		
		// Inialize these values for when the viewer starts again.
		this.doc.$('lbOuterContainer').style.width = '200px';
		this.doc.$('lbOuterContainer').style.height = '200px';
		
		// Safari keeps playing iframe content even when the viewer closes, so clear out the contents.
		if (this.inline && this.safari) {
			// Get a proper reference to the document.
			var iframe = this.doc.$('lbIframe');
			var oDoc = iframe.contentWindow || iframe.contentDocument;
			if (oDoc.document) {
				oDoc = oDoc.document;
			}
			
			// Now write to the document and set some basic styles.
			oDoc.open();
			oDoc.write('<html><head></head><body></body></html>');
			oDoc.close();
		}
		
		// if this was a slideshow, then clear the appropriate timeout id array
		if (this.isSlideshow) {
			for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
			this.isPaused = false;
		}
		
		// Finally, see if we have a "after close" handler set and make the call if so.
		if (!this.isEmpty(this.afterEnd)) {
			var callback = window[this.afterEnd];
			if (typeof callback === 'function') {
				callback(this.args);
			}
		}
		
	}; // end()

//************************************************************************************/
// checkFrame() - Determines if we are in an iFrame or not so we can display properly
//************************************************************************************/
	Lytebox.prototype.checkFrame = function() {
		
		// If we are an iFrame ONLY (framesets are excluded because we can't overlay a frameset).
		// 09/30/2011 - Ensure we don't set this to the parent if we're inside the "lbIframe" container.
		if (window.parent.frames[window.name] && (parent.document.getElementsByTagName('frameset').length <= 0) && window.name != 'lbIframe') {
			this.isFrame = true;
			this.doc = parent.document;
		} else {
			this.isFrame = false;
			this.doc = document;
		}
		
		// Set up shortcut to document.getElementById
		this.doc.$ = this.doc.getElementById;
		
	}; // checkFrame()

//***********************************************************************************/
// getPixelRate() - Determines the rate (number of pixels) that we want to scale PER 
//		call to a setTimeout() function.
//
//	@param	int		iDim		Image/content dimensions
//	@param	int		iCurrent	Current dimensions of the container
//***********************************************************************************/
	Lytebox.prototype.getPixelRate = function(iCurrent, iDim) {
		
		var diff = (iDim > iCurrent) ? iDim - iCurrent : iCurrent - iDim;
		
		if (diff >= 0 && diff <= 100) { return (100 / this.resizeDuration); }
		if (diff > 100 && diff <= 200) { return (150 / this.resizeDuration); }
		if (diff > 200 && diff <= 300) { return (200 / this.resizeDuration); }
		if (diff > 300 && diff <= 400) { return (250 / this.resizeDuration); }
		if (diff > 400 && diff <= 500) { return (300 / this.resizeDuration); }
		if (diff > 500 && diff <= 600) { return (350 / this.resizeDuration); }
		if (diff > 600 && diff <= 700) { return (400 / this.resizeDuration); }
		if (diff > 700) { return (450 / this.resizeDuration); }
		
	}; // getPixelRate()

//*************************************************************************************************************************/
// fadeIn() - Makes an element fade in (appear).
//
//	@param	object	args	An object with properties that determines the settings for fading an element, as well as the element itself.
//
//							id			string		The "id" of the element to fade out
//							opacity		int			The starting opacity (0 by default)
//							speed		int			1 = slowest, 5 = fastest
//							display		string		The objects style.display (block, none, inline, etc.)
//							visibility	string		The objects style.visibility (hidden, visible)
//*************************************************************************************************************************/
	Lytebox.prototype.fadeIn = function(args) {
		
		// Initialize local variables.
		var sId = this.isEmpty(args.id) ? '' : args.id;
		var iSpeed = (this.isEmpty(args.speed) ? 5 : (parseInt(args.speed) > 5 ? 5 : parseInt(args.speed)));
			iSpeed = isNaN(iSpeed) ? 5 : iSpeed;
		var iOpacity = this.isEmpty(args.opacity) ? 0 : parseInt(args.opacity);
			iOpacity = isNaN(iOpacity) ? 0 : iOpacity;
		var sDisplay = this.isEmpty(args.display) ? '' : args.display;
		var sVisibility = this.isEmpty(args.visibility) ? '' : args.visibility;
		var oElement = this.doc.$(sId);
			
		// This variable controls how much to increase the opacity by in each iteration. Special conditions added for various browser versions as the 
		// opacity speed tends to vary. Also, we disable fading the lbIframe container as this can be really choppy if loading lots of media rich content.
		var iIncrement = iSpeed;
		if (/lbImage|lbIframe|lbOverlay|lbBottomContainer|lbTopContainer/.test(sId)) {
			iIncrement = this.ff ? (this.ffVersion >= 6 ? 2 : 5) : (this.safari ? 3 : (this.ieVersion <= 8 ? 10 : 5));
			iIncrement = this.isMobile() ? 20 : iIncrement;
			iIncrement = (sId == 'lbOverlay' ? iIncrement * 2 : iIncrement);
			iIncrement = (sId == 'lbIframe' ? 100 : iIncrement);
		} else if (this.ieVersion == 7 || this.ieVersion == 8) {
			iIncrement = 10;
		}
		
		// Start setting the opacity.
		oElement.style.opacity = (iOpacity / 100);
		oElement.style.filter = "alpha(opacity=" + (iOpacity) + ")";
		
		// If we're showing the image or iframe containers...
		if (iOpacity >= 100 && (sId == 'lbImage' || sId == 'lbIframe')) {
			try { oElement.style.removeAttribute("filter"); } catch(e) {}	/* Fix added for IE Alpha Opacity Filter bug. */

			// Add the appropriate padding to the bottom of the container, if necessary (decision made in function).
			this.fixBottomPadding();
			
		// If we're showing the overlay...
		} else if (iOpacity >= this.maxOpacity && sId == 'lbOverlay') {
			// Clear the overlay timer...
			for (var i = 0; i < this.overlayTimerCount; i++) { window.clearTimeout(this.overlayTimerArray[i]); }
			this.overlayLoaded = true;
			return;
			
		// If we're showing the top or bottom container...
		} else if (iOpacity >= 100 && (sId == 'lbBottomContainer' || sId == 'lbTopContainer')) {
			try { oElement.style.removeAttribute("filter"); } catch(e) {}	/* Fix added for IE Alpha Opacity Filter bug. */
			
			// Clear all the image timers...
			for (var i = 0; i < this.imageTimerCount; i++) { window.clearTimeout(this.imageTimerArray[i]); }
			
			// Here we resize the overlay to ensure that it stretches the length of the page, since there is
			// an issue with white space remaining if the image scales outside of the known page height...
			this.doc.$('lbOverlay').style.height = this.aPageSize[1] + "px";
			
		// If we reach this, then function is being called by the user (library call).
		} else if (iOpacity >= 100) {
			// Clear all the image timers...
			for (var i = 0; i < this.imageTimerCount; i++) { window.clearTimeout(this.imageTimerArray[i]); }
			
		// Otherwise, call ourselves again.
		} else {
			if (sId == 'lbOverlay') {
				this.overlayTimerArray[this.overlayTimerCount++] = setTimeout("$lb.fadeIn({ id: '" + sId + "', opacity: " + (iOpacity + iIncrement) + ", speed: " + iSpeed + " })", 1);
			} else {
				this.imageTimerArray[this.imageTimerCount++] = setTimeout("$lb.fadeIn({ id: '" + sId + "', opacity: " + (iOpacity + iIncrement) + ", speed: " + iSpeed + " })", 1);
			}
		}
		
	}; // fadeIn()

//*************************************************************************************************************************/
// fadeOut() - Makes an element fade out (disappear).
//
//	@param	object	args	An object with properties that determines the settings for fading an element, as well as the element itself.
//
//							id			string		The "id" of the element to fade out
//							opacity		int			The starting opacity (100 by default)
//							speed		int			1 = slowest, 5 = fastest
//							display		string		The objects style.display (block, none, inline, etc.)
//							visibility	string		The objects style.visibility (hidden, visible)
//*************************************************************************************************************************/
	Lytebox.prototype.fadeOut = function(args) {
		
		// Initialize local variables.
		var sId = this.isEmpty(args.id) ? '' : args.id;
		var iSpeed = (this.isEmpty(args.speed) ? 5 : (parseInt(args.speed) > 5 ? 5 : parseInt(args.speed)));
			iSpeed = isNaN(iSpeed) ? 5 : iSpeed;
		var iOpacity = this.isEmpty(args.opacity) ? 100 : parseInt(args.opacity);
			iOpacity = isNaN(iOpacity) ? 100 : iOpacity;
		var sDisplay = this.isEmpty(args.display) ? '' : args.display;
		var sVisibility = this.isEmpty(args.visibility) ? '' : args.visibility;
		var oElement = this.doc.$(sId);
		
		// IE 8 takes forever to fade out elements, so double the speed.
		if (this.ieVersion == 7 || this.ieVersion == 8) {
			iSpeed *= 2;
		}
		
		// Get a reference to the objects style, then start setting the opacity.
		oElement.style.opacity = (iOpacity / 100);
		oElement.style.filter = "alpha(opacity=" + iOpacity + ")";
		
		if (iOpacity <= 0) {
			try {
				if (!this.isEmpty(sDisplay)) {
					oElement.style.display = sDisplay;
				}
				if (!this.isEmpty(sVisibility)) {
					oElement.style.visibility = sVisibility;
				}
			} catch(err) { }
			
			if (sId == 'lbOverlay') {
				this.overlayLoaded = false;
				
				// if this was an iframe, set the source to a blank page and re-initialize so that the iframe is reset (fixes a bug where IE continues playing flash content)
				if (this.isLyteframe) {
					this.doc.$('lbIframe').src = 'about:blank';
					this.initialize();
				}
			} else {
				for (var i = 0; i < this.timerIDCount; i++) { window.clearTimeout(this.timerIDArray[i]); }
			}
		} else if (sId == 'lbOverlay') {
			this.overlayTimerArray[this.overlayTimerCount++] = setTimeout("$lb.fadeOut({ id: '" + sId + "', opacity: " + (iOpacity - (iSpeed * 2)) + ", speed: " + iSpeed + ", display: '" + sDisplay + "', visibility: '" + sVisibility + "' })", 1);
		} else {
			this.timerIDArray[this.timerIDCount++] = setTimeout("$lb.fadeOut({ id: '" + sId + "', opacity: " + (iOpacity - iSpeed) + ", speed: " + iSpeed + ", display: '" + sDisplay + "', visibility: '" + sVisibility + "' })", 1);
		}
		
	}; // fadeOut()
	
//******************************************************/
// resizeW() - Resize the width of an element (animated)
//
//	@param	string		sId
//	@param	int			iCurrentW
//	@param	int			iMaxW
//	@param	int			iPixelRate
//	@param	int			iSpeed
//******************************************************/
	Lytebox.prototype.resizeW = function(sId, iCurrentW, iMaxW, iPixelRate, iSpeed) {
		
		var object = this.doc.$(sId);
		var newW = (this.doAnimations ? iCurrentW : iMaxW);
		object.style.width = (newW) + "px";
		
		if (newW < iMaxW) {
			newW += (newW + iPixelRate >= iMaxW) ? (iMaxW - newW) : iPixelRate;	// increase size
		} else if (newW > iMaxW) {
			newW -= (newW - iPixelRate <= iMaxW) ? (newW - iMaxW) : iPixelRate;	// decrease size
		}
		this.resizeWTimerArray[this.resizeWTimerCount++] = setTimeout("$lb.resizeW('" + sId + "', " + newW + ", " + iMaxW + ", " + iPixelRate + ", " + (iSpeed) + ")", iSpeed);
		
		if (parseInt(object.style.width) == iMaxW) {
			this.wDone = true;
			
			// Clear all the timers for resizing, then load or show the content.
			for (var i = 0; i < this.resizeWTimerCount; i++) { window.clearTimeout(this.resizeWTimerArray[i]); }

			if (this.isLyteframe) {
				this.loadContent();
			} else {
				this.showContent();
			}
		}
		
	}; // resizeW()

//********************************************************/
// resizeH() - Resize the height of an element (animated)
//
//	@param	string		sId
//	@param	int			iCurrentH
//	@param	int			iMaxH
//	@param	int			iPixelRate
//	@param	int			iSpeed
//********************************************************/
	Lytebox.prototype.resizeH = function(sId, iCurrentH, iMaxH, iPixelRate, iSpeed) {
		
		var object = this.doc.$(sId);
		var newH = (this.doAnimations ? iCurrentH : iMaxH);
		
		object.style.height = (newH) + "px";
		if (newH < iMaxH) {
			newH += (newH + iPixelRate >= iMaxH) ? (iMaxH - newH) : iPixelRate;	// increase size
		} else if (newH > iMaxH) {
			newH -= (newH - iPixelRate <= iMaxH) ? (newH - iMaxH) : iPixelRate;	// decrease size
		}
		this.resizeHTimerArray[this.resizeHTimerCount++] = setTimeout("$lb.resizeH('" + sId + "', " + newH + ", " + iMaxH + ", " + iPixelRate + ", " + (iSpeed+.02) + ")", iSpeed+.02);
		
		if (parseInt(object.style.height) == iMaxH) {
			this.hDone = true;
			
			// Clear all the timers for resizing, then resize the width of the container.
			for (var i = 0; i < this.resizeHTimerCount; i++) { window.clearTimeout(this.resizeHTimerArray[i]); }
			this.resizeW('lbOuterContainer', this.wCur, this.resizeWidth + this.borderSize * 2, this.getPixelRate(this.wCur, this.resizeWidth));
		}
		
	}; // resizeH()

//**************************************************/
// getPageScroll() - returns the y page scroll value
//**************************************************/
	Lytebox.prototype.getPageScroll = function() {
		
		if (self.pageYOffset) {
			return this.isFrame ? parent.pageYOffset : self.pageYOffset;
		} else if (this.doc.documentElement && this.doc.documentElement.scrollTop){	 // Explorer 6 Strict
			return this.doc.documentElement.scrollTop;
		} else if (document.body) {// all other Explorers
			return this.doc.body.scrollTop;
		}
		
	}; // getPageScroll()

//*******************************************************************************/
// getPageSize() - Returns array with page width, height and window width, height
//*******************************************************************************/
	Lytebox.prototype.getPageSize = function() {	
	
		var xScroll, yScroll, windowWidth, windowHeight;
		
		if (window.innerHeight && window.scrollMaxY) {
			xScroll = this.doc.scrollWidth;
			yScroll = (this.isFrame ? parent.innerHeight : self.innerHeight) + (this.isFrame ? parent.scrollMaxY : self.scrollMaxY);
		} else if (this.doc.body.scrollHeight > this.doc.body.offsetHeight){ // all but Explorer Mac
			xScroll = this.doc.body.scrollWidth;
			yScroll = this.doc.body.scrollHeight;
		} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
			xScroll = this.doc.getElementsByTagName("html").item(0).offsetWidth;
			yScroll = this.doc.getElementsByTagName("html").item(0).offsetHeight;		
			// Strict mode fixes
			xScroll = (xScroll < this.doc.body.offsetWidth) ? this.doc.body.offsetWidth : xScroll;
			yScroll = (yScroll < this.doc.body.offsetHeight) ? this.doc.body.offsetHeight : yScroll;
		}
		
		if (self.innerHeight) {	// all except Explorer
			windowWidth = (this.isFrame) ? parent.innerWidth : self.innerWidth;
			windowHeight = (this.isFrame) ? parent.innerHeight : self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
			windowWidth = this.doc.documentElement.clientWidth;
			windowHeight = this.doc.documentElement.clientHeight;
			// IE8 compatibility mode fix
			windowWidth = (windowWidth == 0) ? this.doc.body.clientWidth : windowWidth;
			windowHeight = (windowHeight == 0) ? this.doc.body.clientHeight : windowHeight;
		} else if (document.body) { // other Explorers
			windowWidth = this.doc.getElementsByTagName("html").item(0).clientWidth;
			windowHeight = this.doc.getElementsByTagName("html").item(0).clientHeight;		
			// Strict mode fixes...
			windowWidth = (windowWidth == 0) ? this.doc.body.clientWidth : windowWidth;
			windowHeight = (windowHeight == 0) ? this.doc.body.clientHeight : windowHeight;
		}
		
		// for small pages with total height/width less then height/width of the viewport
		var pageHeight = (yScroll < windowHeight) ? windowHeight : yScroll;
		var pageWidth = (xScroll < windowWidth) ? windowWidth : xScroll;
		
		return new Array(pageWidth, pageHeight, windowWidth, windowHeight);
		
	}; // getPageSize()

//**********************************************************************************************************/
// toggleObjects() - Toggles embedded objects so they don't appear above the overlay/lytebox.
//
//	@param	string	sState	hidden|visible
//**********************************************************************************************************/
	Lytebox.prototype.toggleObjects = function(sState) {
		
		var objects = this.doc.getElementsByTagName("object");
		for (var i = 0; i < objects.length; i++) {
			objects[i].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
		}
	
		var embeds = this.doc.getElementsByTagName("embed");
		for (var i = 0; i < embeds.length; i++) {
			embeds[i].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
		}
		
		if (this.isFrame) {
			for (var i = 0; i < parent.frames.length; i++) {
				try {
					objects = parent.frames[i].window.document.getElementsByTagName("object");
					for (var j = 0; j < objects.length; j++) {
						objects[j].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
					}
				} catch(e) { /* ignore */ }
				
				try {
					embeds = parent.frames[i].window.document.getElementsByTagName("embed");
					for (var j = 0; j < embeds.length; j++) {
						embeds[j].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
					}
				} catch(e) { /* ignore */ }
			}
		}
		
	}; // toggleObjects()

//**********************************************************************************************************/
// toggleSelects() - Toggles select boxes between hidden and visible states, including those in iFrames
//
//	@param	string	sState	hidden|visible
//**********************************************************************************************************/
	Lytebox.prototype.toggleSelects = function(sState) {
		
		// hide in the parent frame, then in child frames
		var selects = this.doc.getElementsByTagName("select");
		for (var i = 0; i < selects.length; i++ ) {
			selects[i].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
		}
	
		if (this.isFrame) {
			for (var i = 0; i < parent.frames.length; i++) {
				try {
					selects = parent.frames[i].window.document.getElementsByTagName("select");
					for (var j = 0; j < selects.length; j++) {
						selects[j].style.visibility = (sState == "hide") ? 'hidden' : 'visible';
					}
				} catch(e) { /* ignore */ }
			}
		}
		
	}; // toggleSelects()

//********************************************************************************/
// pause() - Pauses code execution for specified time. Uses busy code, not good.
//
//	@param	int		iMillis		Milliseconds
//********************************************************************************/
	Lytebox.prototype.pause = function(iMillis) {
		
		var now = new Date();
		var exitTime = now.getTime() + iMillis;
		while (true) {
			now = new Date();
			if (now.getTime() > exitTime) { return; }
		}
		
	}; // pause()

//************************************************************************/
// combine() - Combines objects of anchors and areas into a single array.
//
//	@param	array	aAnchors	Array of anchor "a" elements
//	@param	array	aAreas		Array of area map elements
//************************************************************************/
	Lytebox.prototype.combine = function(aAnchors, aAreas) {
		
		var lyteLinks = [];
		for (var i = 0; i < aAnchors.length; i++) {
			lyteLinks.push(aAnchors[i]);
		}
		for (var i = 0; i < aAreas.length; i++) {
			lyteLinks.push(aAreas[i]);
		}
		return lyteLinks;
		
	}; // combine()

//************************************************************************/
// removeDuplicates() - Returns an array with unique links (index [x][0])
//
//	@param	array	aArray	The original array to check
//	@return	array	aNew	The array minus any duplicates
//************************************************************************/
	Lytebox.prototype.removeDuplicates = function (aArray) {
		
		var aNew = new Array();
    	o:for(var i = 0, n = aArray.length; i < n; i++) {
			for(var x = 0, y = aNew.length; x < y; x++) {
                if (aNew[x][0].toLowerCase() == aArray[i][0].toLowerCase()) { continue o; }
        	}
        	aNew[aNew.length] = aArray[i];
    	}
    	return aNew;
		
	}; // removeDuplicates()

//****************************************************************************************************/
// printWindow() - Sets up the new window for printing, then calls $lb.printContent() to print.
//****************************************************************************************************/
	Lytebox.prototype.printWindow = function () {
		
		// Set the width/height of the window according to the image dimensions. We add 20 because some browsers like to add padding/margins,
		// which causes the image to scale down slightly.
		var w = this.isLyteframe ? 800 : this.imgPreloader.width + 20;
		var h = this.isLyteframe ? 600 : this.imgPreloader.height + 20;
		var left = parseInt((screen.availWidth/2) - (w/2));
		var top = parseInt((screen.availHeight/2) - (h/2));
		var wOpts = "width=" + w + ",height=" + h + ",left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top + "directories=0,location=0,menubar=0,resizable=0,scrollbars=0,status=0,titlebar=0,toolbar=0";
		var d = new Date();
		var wName = 'Print' + d.getTime();
		var wUrl = document.getElementById(this.printId).src;
		
		// Note that wContent is referenced in printContent, hence the reason it's being saved to an object.
		this.wContent = window.open(wUrl, wName, wOpts);
		this.wContent.focus();
		var t = setTimeout("$lb.printContent()",1000);
		
	}; // printWindow()

//************************************************************************************/
// printContent() - Prints the contents of the loaded window when the document loads.
//************************************************************************************/
	Lytebox.prototype.printContent = function() {
		
		// Adding a try/catch block as external sources (in an iframe) disallow access to readyState and will throw exception.
		try {
			if (this.wContent.document.readyState == 'complete') {
				this.wContent.print();
				this.wContent.close();
				this.wContent = null;
			} else {
				var t = setTimeout("$lb.printContent()",1000);
			}
		} catch(e) { }
		
	}; // printContent()

//**********************************************************************************************************/
// setOptions(sOptions) - Sets up the look/feel of the viewer as well as other configurable options, as shown below.
//
//	@param	string	sOptions	Possible values shown below
//
//	* Global Viewer Options
//		-	inheret:true|false
//		-	hideObjects:true|false
//		-	autoResize:true|false
//		-	doAnimations:true|false
//		-	animateOverlay:true|false
//		-	forceCloseClick:true|false
//		-	refreshPage:true|false
//		-	showPrint:true|false
//		-	navType:1|2|3
//		-	titleTop:true|false
//		-	navTop:true|false
//		-	beforeStart:<functionName>
//		-	afterStart:<functionName>
//		-	beforeEnd:<functionName>
//		-	afterEnd:<functionName>
//
//	* HTML Content / Embedded Media Only
//		-	scrollbars:auto|yes|no
//		-	scrolling:auto|yes|no
//		-	width:#%|#px
//		-	height:#%|#px
//		-	loopPlayback:true|false
//		-	autoPlay:true|false
//		-	autoEmbed:true|false
//
//	* Slideshow Only
//		-	slideInterval:#
//		-	showNavigation:false|true
//		-	showClose:true|false
//		-	showDetails:true|false
//		-	showPlayPause:true|false
//		-	autoEnd:true|false
//		-	pauseOnNextClick:false|true
//		-	pauseOnPrevClick:true|false
//		-	loopSlideshow:true|false
//**********************************************************************************************************/
	Lytebox.prototype.setOptions = function(sOptions) {
				
		// Reset all options.
		this.args = '';
		this.group = '';
		this.inline = false;
		this.hideObjects = this.__hideObjects;
		this.autoResize = this.__autoResize;
		this.doAnimations = this.__doAnimations;
		this.animateOverlay = this.__animateOverlay;
		this.forceCloseClick = this.__forceCloseClick;
		this.refreshPage = this.__refreshPage;
		this.showPrint = this.__showPrint;
		this.navType = this.__navType;
		this.titleTop = this.__titleTop;
		this.navTop = this.__navTop;
		this.beforeStart = this.__beforeStart;
		this.afterStart = this.__afterStart
		this.beforeEnd = this.__beforeEnd;
		this.afterEnd = this.__afterEnd;
		this.scrolling = this.__scrolling;
		this.width = this.__width;
		this.height = this.__height;
		this.loopPlayback = this.__loopPlayback;
		this.autoPlay = this.__autoPlay;
		this.autoEmbed = this.__autoEmbed;
		this.slideInterval = this.__slideInterval;
		this.showNavigation = this.__showNavigation;
		this.showClose = this.__showClose;
		this.showDetails = this.__showDetails;
		this.showPlayPause = this.__showPlayPause;
		this.autoEnd = this.__autoEnd;
		this.pauseOnNextClick = this.__pauseOnNextClick;
		this.pauseOnPrevClick = this.__pauseOnPrevClick;
		this.loopSlideshow = this.__loopSlideshow;
		
		// Each configurable option will be space separated, so start by splitting the sting into an array.
		var sName = sValue = '';
		var aSetting = null;
		var aOptions = sOptions.split(' ');
		
		// Loop to set each option.
		for (var i = 0; i < aOptions.length; i++) {
			// The option itself should be a name:value pair, so split into what should be a two element array.
			aSetting = aOptions[i].split(':');
			sName = (aSetting.length > 1 ? this.trim(aSetting[0]).toLowerCase() : '');
			sValue = (aSetting.length > 1 ? this.trim(aSetting[1]): '');
			
			// Now we'll run some tests to determine which particular option is being set, and what the value should be.
			switch(sName) {
			// Global Viewer Options
				case 'group':			this.group = (sName == 'group' ? (!this.isEmpty(sValue) ? sValue.toLowerCase() : '') : ''); break;
				case 'hideobjects':		this.hideObjects = (/true|false/.test(sValue) ? (sValue == 'true') : this.__hideObjects); break;
				case 'autoresize':		this.autoResize = (/true|false/.test(sValue) ? (sValue == 'true') : this.__autoResize); break;
				case 'doanimations':	this.doAnimations = (/true|false/.test(sValue) ? (sValue == 'true') : this.__doAnimations); break;
				case 'animateoverlay':	this.animateOverlay = (/true|false/.test(sValue) ? (sValue == 'true') : this.__animateOverlay); break;
				case 'forcecloseclick':	this.forceCloseClick = (/true|false/.test(sValue) ? (sValue == 'true') : this.__forceCloseClick); break;
				case 'refreshpage':		this.refreshPage = (/true|false/.test(sValue) ? (sValue == 'true') : this.__refreshPage); break;
				case 'showprint':		this.showPrint = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showPrint); break;
				case 'navtype':			this.navType = (/[1-3]{1}/.test(sValue) ? parseInt(sValue) : this.__navType); break;
				case 'titletop':		this.titleTop = (/true|false/.test(sValue) ? (sValue == 'true') : this.__titleTop); break;
				case 'navtop':			this.navTop = (/true|false/.test(sValue) ? (sValue == 'true') : this.__navTop); break;
				case 'beforestart':		this.beforeStart = (!this.isEmpty(sValue) ? sValue : this.__beforeStart); break;
				case 'afterstart':		this.afterStart = (!this.isEmpty(sValue) ? sValue : this.__afterStart); break;
				case 'beforeend':		this.beforeEnd = (!this.isEmpty(sValue) ? sValue : this.__beforeEnd); break;
				case 'afterend':		this.afterEnd = (!this.isEmpty(sValue) ? sValue : this.__afterEnd); break;
				case 'args': 			this.args = (!this.isEmpty(sValue) ? sValue : ''); break;
			// HTML Content / Embedded Media Only
				case 'scrollbars':		this.scrolling = (/auto|yes|no/.test(sValue) ? sValue : this.__scrolling); break;
				case 'scrolling':		this.scrolling = (/auto|yes|no/.test(sValue) ? sValue : this.__scrolling); break;
				case 'width':			this.width = (/\d(%|px|)/.test(sValue) ? sValue : this.__width); break;
				case 'height':			this.height = (/\d(%|px|)/.test(sValue) ? sValue : this.__height); break;
				case 'loopplayback':	this.loopPlayback = (/true|false/.test(sValue) ? (sValue == 'true') : this.__loopPlayback); break;
				case 'autoplay':		this.autoPlay = (/true|false/.test(sValue) ? (sValue == 'true') : this.__autoPlay); break;
				case 'autoembed':		this.autoEmbed = (/true|false/.test(sValue) ? (sValue == 'true') : this.__autoEmbed); break;
				case 'inline':			this.inline = (/true|false/.test(sValue) ? (sValue == 'true') : false);
			// Slideshow Only
				case 'slideinterval':	this.slideInterval = (/\d/.test(sValue) ? parseInt(sValue) : this.__slideInterval); break;
				case 'shownavigation':	this.showNavigation = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showNavigation); break;
				case 'showclose':		this.showClose = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showClose); break;
				case 'showdetails':		this.showDetails = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showDetails); break;
				case 'showplaypause':	this.showPlayPause = (/true|false/.test(sValue) ? (sValue == 'true') : this.__showPlayPause); break;
				case 'autoend':			this.autoEnd = (/true|false/.test(sValue) ? (sValue == 'true') : this.__autoEnd); break;
				case 'pauseonnextclick': this.pauseOnNextClick = (/true|false/.test(sValue) ? (sValue == 'true') : this.__pauseOnNextClick); break;
				case 'pauseonprevclick': this.pauseOnPrevClick = (/true|false/.test(sValue) ? (sValue == 'true') : this.__pauseOnPrevClick); break;
				case 'loopslideshow':	this.loopSlideshow = (/true|false/.test(sValue) ? (sValue == 'true') : this.__loopSlideshow); break;
			}
		}
		
	}; // setOptions()
	
//**********************************************************************************************************/
// buildObject() - Builds an embedded object using the appropriate class ID, codebase, options, etc. This
//		object is returned as a string and will be used for iframe content (media player).
//
//	@param	int		w		Container width
//	@param	int		h		Container height
//	@param	string	url		URL of content to play
//
//	@return	string	object	A string containing the embedded object HTML.
//**********************************************************************************************************/
	Lytebox.prototype.buildObject = function(w, h, url) {
		
		var object = '';
		var classId = '';
		var codebase = '';
		var pluginsPage = '';
		var auto = this.autoPlay ? 'true' : 'false';
		var loop = this.loopPlayback ? 'true' : 'false';
		var type = url.match(/.mov|.avi|.wmv|.mpg|.mpeg|.swf/i);
		
		switch(type[0]) {
			// QuickTime (.mov)
			case '.mov':
				codebase = 'http://www.apple.com/qtactivex/qtplugin.cab';
				pluginsPage = 'http://www.apple.com/quicktime/';
				classId = 'clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B';
				object 	= '<object classid="' + classId + '" width="' + w + '" height="' + h + '" codebase="' + codebase + '">'
						+ '<param name="src" value="' + url + '">'
						+ '<param name="autoplay" value="' + auto + '">'
						+ '<param name="loop" value="' + loop + '">'
						+ '<param name="controller" value="true">'
						+ '<embed src="' + url + '" width="' + w + '" height="' + h + '" autoplay="' + auto + '" loop="' + loop + '" controller="true" pluginspage="' + pluginsPage + '"></embed>'
						+ '</object>';
				if (this.getQuicktimeVersion() <= 0) {
					object	= '<div style="padding:1em;">'
							+ '<h2>QUICKTIME PLAYER</h2>'
							+ '<p>Content on this page requires a newer version of QuickTime. Please click the image link below to download and install the latest version.</p>'
							+ '<p><a href="http://www.apple.com/quicktime/" target="_blank"><img src="http://images.apple.com/about/webbadges/images/qt7badge_getQTfreeDownload.gif" alt="Get QuickTime" border="0" /></a></p>'
							+ '</div>';
				}
				break;				
			
			// Windows Media (.avi, .mpg, .mpeg, .wmv)
			// Non IE browsers will play these files using QuickTime, and if no codec is found QT tends to stutter. Note that in chrome you MUST put the full URL, 
			// otherwise it won't work. More on that here: http://www.google.com/support/forum/p/Chrome/thread?tid=2246e55fc37cea59&hl=en
			case '.avi':
			case '.mpg':
			case '.mpeg':
			case '.wmv':
				classId = 'clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B';			
				object 	= '<object classid="' + classId + '" width="' + w + '" height="' + h + '" codebase="' + codebase + '">'
						+ '<param name="src" value="' + url + '">'
						+ '<param name="autoplay" value="' + auto + '">'
						+ '<param name="loop" value="' + loop + '">'
						+ '<param name="controller" value="true">'
						+ '<object type="video/quicktime" data="' + url + '" width="' + w + '" height="' + h + '">'
						+ '<param name="controller" value="false">'
						+ '<param name="autoplay" value="' + auto + '">'
						+ '<param name="loop" value="' + loop + '">'
						+ '</object>' 
						+ '</object>';
				break;
			
			// Flash (.swf) - Works in FF, IE, Chrome, and Opera (latest versions)
			case '.swf':
				classId = 'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000';
				object 	= '<object classid="' + classId + '" width="' + w + '" height="' + h + '" codebase="' + codebase + '">'
						+ '<param name="movie" value="' + url + '">'
						+ '<param name="quality" value="high">'
						+ '<param name="wmode" value="opaque">'
						+ '<!--[if !IE]>-->'
						+ '<object type="application/x-shockwave-flash" data="' + url + '" width="' + w + '" height="' + h + '">'
						+ '<!--<![endif]-->'
						+ '<param name="quality" value="high">'
						+ '<param name="wmode" value="opaque">'
						+ '<div style="padding:1em;">'
						+ '<h2>FLASH PLAYER</h2>'
						+ '<p>Content on this page requires a newer version of Adobe Flash Player. Please click the image link below to download and install the latest version.</p>'
						+ '<p><a href="http://www.adobe.com/go/getflashplayer" target="_blank"><img src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" alt="Get Adobe Flash player" border="0" /></a></p>'
						+ '</div>'
						+ '<!--[if !IE]>-->'
						+ '</object>'
						+ '<!--<![endif]-->'
						+ '</object>';
				break;
		}
		
		return object;
		
	}; // buildObject();

//**********************************************************************************************************/
// getQuicktimeVersion() - Returns the QuickTime version, if installed/enabled.
//
//	@return	integer		-1 if no player was found, a positive integer otherwise.
//**********************************************************************************************************/	
	Lytebox.prototype.getQuicktimeVersion = function() {
		
		var agent = navigator.userAgent.toLowerCase(); 
		var version = -1;
   
		// NS3+, Opera3+, IE5+ Mac (support plugin array): check for Quicktime plugin in plugin array
		if (navigator.plugins != null && navigator.plugins.length > 0) {
			for (i=0; i < navigator.plugins.length; i++ ) {
				var plugin = navigator.plugins[i];
				if (plugin.name.indexOf('QuickTime') > -1) {
					version = parseFloat(plugin.name.substring(18));
				}
			}
		// IE4+ Win32:  attempt to create an ActiveX object
		} else if (this.autoEmbed && agent.indexOf('msie') != -1 && parseInt(navigator.appVersion) >= 4 && agent.indexOf('win') != -1 && agent.indexOf('16bit') == -1) {
			var control = null;
			try {
				control = new ActiveXObject('QuickTime.QuickTime');
			} catch (e) { }
			if (control) {
				// In case QuickTimeCheckObject.QuickTimeCheck does not exist
				isInstalled = true;
			}			
			try {
				// This generates a user prompt in IE 7+
				control = new ActiveXObject('QuickTimeCheckObject.QuickTimeCheck');
			} catch (e) { return; }
			if (control) {
				// In case QuickTime.QuickTime does not exist
				isInstalled = true;
				
				// Get version
				version = control.QuickTimeVersion.toString(16); // Convert to hex
				version = version.substring(0, 1) + '.' + version.substring(1, 3);
				version = parseInt(version);
			}
		}
		return version;
		
	}; // getQuicktimeVersion()
	
//**********************************************************************************************************/
// findPos() - Returns the left/top position of an element, as well as the element's height.
//
//	@param	object	HTML element
//	@return	array	[left,top,height]
//**********************************************************************************************************/	
	Lytebox.prototype.findPos = function(el) {
		
		// IE in QuirksMode chokes here, so return preset values.
		if (this.ie && this.doc.compatMode == 'BackCompat') {
			return [0, 16, 12];
		}
		
		var left = 0;
		var top = 0;
		var height = 0;
		height = el.offsetHeight + 6;
		
		if (el.offsetParent) {
			do {
				left += el.offsetLeft;
				top += el.offsetTop;
			} while (el = el.offsetParent);
		}
		return [left, top, height];
		
	}; // findPos()

//**********************************************************************************************************/
// isMobile() - Will attempt to determine if we're dealing with a mobile device/tablet.
//
//	@return	boolean		true|false
//**********************************************************************************************************/
	Lytebox.prototype.isMobile = function() {
		
		// Get the user agent.		
		var ua = navigator.userAgent;
		
		// Will return true if a mobile device is detected, false otherwise.
		return (ua.match(/ipad/i) != null)
			|| (ua.match(/ipod/i) != null)
			|| (ua.match(/iphone/i) != null)
			|| (ua.match(/android/i) != null)
			|| (ua.match(/opera mini/i) != null)
			|| (ua.match(/blackberry/i) != null)
			|| (ua.match(/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i) != null)
			|| (ua.match(/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i) != null)
			|| (ua.match(/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i) != null);
			
	}; // isMobile()
	
//**************************************************************************************************************************/
// validate() - Validates a given value based on the incoming options (regex, type to validate). The incoming
//		value will be converted to a string before being tested.
//
//	@param	object	args	An object with properties that determine how the validator will function. The
//							following properties are supported:
//
//							value			string		The value that we want to test. Should be excluded if passing in an id.
//							id				string		The id of the form element to test (instead of a value).
//							element			object		A reference to the element. Can be used in place of id.
//
//							regex			string		If a regular expression is passed in, this will be used instead of a type validator
//							type			string		The type of validation to perform. The following values are supported:
//
//													- alnum
//													- alpha
//													- between
//													- ccnum
//													- date
//													- digits
//													- email
//													- float
//													- image
//													- integer | int
//													- length | len
//													- phone
//													- notempty
//													- ssn
//													- url
//													- zip
//
//							min				int			The minimum value or length of a string (between, len, length)
//							max				int			The maximum value or length of a string (between, len, length)
//							inclusive		boolean		true (default) to include min/max values (between), false otherwise
//							allowComma		boolean		true (default) to allow commas (between, float), false otherwise
//							allowWhiteSpace	boolean		true (default) to allow white space (alnum, alpha), false otherwise
//							ccType			string		The credit card type (visa|mc|amex|diners|discover|jcb)
//							imageType		string		The specific image type to test for (gif|jpg|png|bmp|eps|etc)
//
//	@return	boolean	bValid	true if input passes validation, false otherwise
//**************************************************************************************************************************/
	Lytebox.prototype.validate = function(args) {
		
		// Initialize.
		var reTest = sName = '';
		var bValid = false;
		var oElement = this.isEmpty(args.id) ? (this.isEmpty(args.element) ? null : args.element) : document.getElementById(args.id);
		var sInput = this.isEmpty(args.value) ? '' : String(args.value);
		var sType = this.isEmpty(args.type) ? '' : String(args.type).toLowerCase();
		var sRegex = this.isEmpty(args.regex) ? '' : args.regex;
		
		// These are specific options that will be used for certain types of validators.
		var sCCType = (/visa|mc|amex|diners|discover|jcb/.test(args.ccType) ? args.ccType : '');
		var sImageType = this.isEmpty(args.imageType) ? '' : String(args.imageType.toLowerCase());
		var iMin = (/^\d+$/.test(args.min) ? parseInt(args.min) : 0);
		var iMax = (/^\d+$/.test(args.max) ? parseInt(args.max) : 0);
		var bInclusive = args.inclusive ? true : (/true|false/.test(args.inclusive) ? (args.inclusive == 'true') : true);
		var bAllowComma = args.allowComma ? true : (/true|false/.test(args.allowComma) ? (args.allowComma == 'true') : true);
		var bAllowWhitespace = args.allowWhiteSpace ? true : (/true|false/.test(args.allowWhiteSpace) ? (args.allowWhiteSpace == 'true') : true);
		
		// Ensure that we're actually getting something to test. If not, just return false immediately.
		if ((this.isEmpty(sInput) && this.isEmpty(oElement)) || (this.isEmpty(sType) && this.isEmpty(sRegex))) {
			return false;
		}
		
		// Get the value to test.
		var sInput = this.isEmpty(sInput) ? oElement.value : sInput;
		
		// If a custom regular expression was passed in, test the input string against that.
		if (!this.isEmpty(sRegex)) {
			
			bValid = sRegex.test(sInput);

		// Otherwise, determine the type of validation to do and run the test.
		} else {
			
			switch(sType) {
				// Alphanumeric (alphabetical characters and digits)
				//	 - checks bAllowWhitespace
				// 	 - no whitespace	(pass: abc, abc123, 123) (fail: a#b!c, ab c)
				//	 - allow whitespace (pass: a b c, abc 123, 12 3) (fail: ab !c, ab#c)
				case 'alnum':
					bValid = (bAllowWhitespace ? /^[a-z0-9\s]+$/i.test(sInput) : /^[a-z0-9]+$/i.test(sInput)); break;
				
				// Alpha (alphabetical characters)
				//	 - checks bAllowWhitespace
				// 	 - no whitespace	(pass: abc) (fail: ab c, ab12, ab!c)
				//	 - allow whitespace	(pass: abc efg) (fail: ab1 c, #ab c)
				case 'alpha':
					bValid = (bAllowWhitespace ? /^[a-z\s]+$/i.test(sInput) : /^[a-z]+$/i.test(sInput)); break;
					
				// Between (input is between 2 values)
				//	 - checks bAllowComma, bInclusive, iMin, and iMax
				//	 - inclusive true	(pass: 3 is between 3 and 5) (fail: 2 is NOT between 3 and 5)
				//	 - inclusive false	(pass: 4 is between 3 and 5) (fail: 3 is NOT between 3 and 5)
				case 'between':
					var iInput = bAllowComma ? parseInt(sInput.replace(/\,/g,'')) : parseInt(sInput);
					bValid = (bInclusive ? (iInput >= iMin && iInput <= iMax) : (iInput > iMin && iInput < iMax)); break;
					
				// Credit Card Numbers (visa|mc|amex|diners|discover|jcb)
				//	 - checks sCCType
				case 'ccnum':
					if (this.isEmpty(sCCType)) {
						bValid = /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/.test(sInput); break;
					} else {
						switch(sCCType) {
							case 'visa':
								bValid = /^4[0-9]{12}(?:[0-9]{3})?$/.test(sInput); break;
							case 'mc':
								bValid = /^5[1-5][0-9]{14}$/.test(sInput); break;
							case 'amex':
								bValid = /^3[47][0-9]{13}$/.test(sInput); break;
							case 'diners':
								bValid = /^3(?:0[0-5]|[68][0-9])[0-9]{11}$/.test(sInput); break;
							case 'discover':
								bValid = /^6(?:011|5[0-9]{2})[0-9]{12}$/.test(sInput); break;
							case 'jcb':
								bValid = /^(?:2131|1800|35\d{3})\d{11}$/.test(sInput); break;
							default:
								bValid = false;
						}
					}
					
				// Date (uses built-in JS date to determine if a date is in a valid format)
				case 'date':
					var date = new Date(sInput);
					bValid = !(date.toString() == 'NaN' || date.toString() == 'Invalid Date'); break;
				
				// Digits (pass: 1234) (fail: 12.3, 12e3, +5)
				case 'digits':
					bValid = /^\d+$/.test(sInput); break;
					
				// Email (pass: user.name@domain.com, 12name@localhost.me)
				case 'email':
					bValid = /^([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+$/i.test(sInput); break;
					
				// Floating Point / Number Matching (pass: -5, 5.0, .05, 1,234.5) (fail: 1.5e6)
				//	 - checks bAllowComma
				case 'float':
					bValid = /^[-+]?[0-9]*\.?[0-9]+$/.test(bAllowComma ? sInput.replace(/\,/g,'') : sInput); break;
					
				// Image (pass: filename.jpeg, http://host/file.png) (fail: file.htm, host.com/file.jpg1)
				case 'image':
					if (this.isEmpty(sImageType)) {
						bValid = /^(png|jpg|jpeg|gif)$/i.test(sInput.split('.').pop()); break;
					} else {
						bValid = (sInput.split('.').pop().toLowerCase().match(sImageType) ? true : false); break;
					}
					
				// Integer (pass: -5, 5, 1,234) (fail: 1.234)
				case 'int':
				case 'integer':
					bValid = /^[-+]?\d+$/.test(sInput.replace(/\,/g,'')); break;
					
				// String Length
				//	 - checks iMin and iMax
				//	 - iMin and iMax used to check that string is between two values (inclusive)
				//	 - if iMin and iMax are the same, then string length has to be exact
				case 'len':
				case 'length':
					bValid = (iMin == iMax) ? (sInput.length == iMin) : (sInput.length >= iMin && sInput.length <= iMax); break;
					
				// Phone number (US format)
				// (pass: 1112223333, 111222-3333, 111-222-3333, (111)2223333, (111) 222-3333)
				// (fail: 11112223333, 111222-3333, 111 - 222 - 3333)
				case 'phone':
					bValid = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/.test(sInput); break;
					
				// Not Empty (fail: null, 'null', '', 'undefined')
				case 'notempty':
					bValid = !this.isEmpty(sInput); break;
					
				// Social Security Number (pass: 111-22-3333, 111223333)
				case 'ssn':
					bValid = /^[0-9]{3}\-?[0-9]{2}\-?[0-9]{4}$/.test(sInput); break;
					
				// URL - Credit goes to JOHN GRUBER for this awesome regex
				case 'url':
					bValid = /\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'".,<>?«»""'']))/i.test(sInput); break;
					
				// Zip +4 (pass: 12345, 12345-6789) (fail: 12345-, 123456789)
				case 'zip':
					bValid = /^\d{5}$|^\d{5}-\d{4}$/.test(sInput); break;

			}
			
		}
		
		return bValid;
			
	}; // validate()
	
//*************************************************************************************************************************************/
// ajax() - Sends an Ajax request to a specific URL. The GET and POST methods are supported, and all requests
//		are asynchronous. The "args" parameter should be in object notation.
//
//	@param	object	args	An object with properties that determine how the Ajax call will be made. The
//							following properties are supported, along with the data type expected.
//
//							url			string		The url where the request should be sent (e.g. 'script.php'). Note that if using
//													GET, then the url should contain all the relevant data in the QS (&name=value pairs)
//							method		string		Only GET (default) and POST are supported.
//							form		string		The 'name' or 'id' of the form to submit. Note that this value is only needed if
//													using the POST method.
//							updateId	string		The 'id' of the node to write back to when the response is received.
//							success		function	The function to call onSuccess.
//							fail		function	The function to call onFail.
//							cache		boolean		true (default) to enable caching, false to add a timestamp to the url.
//							stripTags	boolean		true to strip HTML tags from the input, false otherwise
//							timeout		int			The number of milliseconds before we abort (timeout). An aborted request is treated
//													the same as a failed attempt, so anything assigned to the "fail" handler will run.
//*************************************************************************************************************************************/
	Lytebox.prototype.ajax = function(args) {
		
		// Create a new HTTP request object.
		var iIndex = this.http.length;
		var oRequest = this.getRequestObject();
		
		// We store this so that we can abort properly.
		this.http[iIndex] = oRequest;
		
		// Now set up our HTTP args object.
		var oHttpArgs = args;
		
		// Save the "index" of the current request object so we can kill it later.
		oHttpArgs.index = iIndex;
			
		// Ensure that we have the minimum required properties set as these will be used later.
		oHttpArgs.method = !(/get|post/i.test(oHttpArgs.method)) ? 'get' : oHttpArgs.method;
		oHttpArgs.cache = !(/true|false/.test(oHttpArgs.cache)) ? true : (oHttpArgs.cache == 'true' || oHttpArgs.cache);
			
		// If a valid timeout is set, then we'll do a setTimeout to abort the Ajax request.
		if (!this.isEmpty(oHttpArgs.timeout) && (/^\d+$/.test(oHttpArgs.timeout))) {
			oHttpArgs.timerId = setTimeout("$lb.http["+iIndex+"].abort()", oHttpArgs.timeout);
		}
			
		// Define state change handler. Note that "this" will refer to the request object, not Lytebox. We're also defining a closure
		// so that we can pass in our oHttpArgs object.
		oRequest.onreadystatechange = function() {
			return function() {
				// Request is OK
				if (oRequest.readyState == 4 && oRequest.status == 200) {
					// If we have an element to write the contents to, make it so.
					if (document.getElementById(oHttpArgs.updateId)) {
						// Try/catch added for IE (quirks mode), which will throw an exception if writing block level elements that doesn't 
						// support them. An example of this is writing a "<li>" tag inside a paragraph tag with a parent "<ul>" container.
						try {
							document.getElementById(oHttpArgs.updateId).innerHTML = oRequest.responseText;
						} catch(e) { alert(e.description ); };
					}
												
					// Verify that this is a valid function, and if so call it.
					if (typeof oHttpArgs.success === 'function') {
						oHttpArgs.success(oRequest);
					}
					
					// Clear the abort timer and kill the request object reference.
					window.clearTimeout(oHttpArgs.timerId);
					$lb.http[oHttpArgs.index] = null;
				// Request failed
				} else if (oRequest.readyState == 4 && oRequest.status != 200) {							
					// Verify that this is a valid function, and if so call it.
					if (typeof oHttpArgs.fail === 'function') {
						oHttpArgs.fail(oRequest);
					}
					// Clear the abort timer and kill the request object reference.
					window.clearTimeout(oHttpArgs.timerId);
					$lb.http[oHttpArgs.index] = null;
				}
			} (oRequest, oHttpArgs);
		}
			
		// Send the request.
		if (oHttpArgs.method.toLowerCase() == 'post') {
			// Serialize the parameters (name/value pairs) that we'll "post" and add a timestamp to disable caching (if necessary).
			var oForm = document.getElementById(oHttpArgs.form);
			var bStripTags = !(/true|false/.test(args.stripTags)) ? false : (args.stripTags == 'true' || args.stripTags);
			var sParams = (oForm == null ? this.serialize({ name: oHttpArgs.form, stripTags: bStripTags }) : this.serialize({ element: oForm, stripTags: bStripTags }));
			var sTimestamp = (!oHttpArgs.cache ? ((/\&/.test(sParams)) ? '&' : '') + new Date().getTime() : '');
			
			// Set the request headers, and send it on its merry way.
			oRequest.open('post', oHttpArgs.url, true);
			oRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
			oRequest.send(sParams + sTimestamp);
		} else {
			// Add a timestamp to disable caching (if necessary).
			var sTimestamp = (!oHttpArgs.cache ? ((/\?/.test(oHttpArgs.url)) ? '&' : '?') + new Date().getTime() : '');
			oRequest.open('get', oHttpArgs.url  + sTimestamp, true);
			oRequest.send();
		}
		
	}; // ajax()
	
//**********************************************************************************************************/
// serialize() - Returns a text string in standard URL-encoded notation, given a form name or id. The return
//		string is essentially a set of name/value pairs that will be sent with an Ajax request.
//
//	@param	object	args	An object with properties that determine how we'll get a handle on the form.
//
//							id			string		The "id" of the form.
//							name		string		The "name" of the form.
//							element		object		A reference to the form. Can be used in place of id or name.
//							stripTags	boolean		true to strip HTML tags from the input, false otherwise
//
//	@return	string	sParams		The serialized form elements (&name=value pairs)
//**********************************************************************************************************/
	Lytebox.prototype.serialize = function(args) {
		
		// Initialize
		var sParams = sValue = '';
		var bStripTags = !(/true|false/.test(args.stripTags)) ? false : (args.stripTags == 'true' || args.stripTags);
		
		// Get the appropriate form elements.
		var oElements = this.isEmpty(args.id) ? (this.isEmpty(args.element) ? null : args.element) : document.getElementById(args.id);
		if (oElements == null) {
			for (var i = 0; i < document.forms.length; i++) {
				if (document.forms[i].name == args.name) {
					oElements = document.forms[i].elements;
				}
			}
		}
		
		// Now build our parameters, noting that we ignore values for unchecked boxes, radio buttons, disabled fields, and the reset button.				
		for (var i = 0; i < oElements.length; i++) {
			if ((oElements[i].type == 'checkbox' && !oElements[i].checked) ||
				(oElements[i].type == 'radio' && !oElements[i].checked) ||
				(oElements[i].disabled) || (oElements[i].name == '') || (oElements[i].type == 'reset')) {
				continue;
			}
			
			// Handle multi-select list.
			if (oElements[i].type == 'select-multiple') {
				for (var j = 0; j < oElements[i].options.length; j++) {
					if (oElements[i].options[j].selected == true) {
						sParams += (sParams == '' ? '' : '&') + oElements[i].name + '=' + encodeURIComponent(oElements[i].options[j].value);
					}
				}
			} else {
				sValue = bStripTags ? this.stripTags({ value: oElements[i].value }) : oElements[i].value;
				sParams += (sParams == '' ? '' : '&') + oElements[i].name + '=' + encodeURIComponent(sValue);
			}
		}
		
		return sParams;
			
	}; // serialize()
	
//**********************************************************************************************************/
// getRequestObject() - Return a valid XMLHttpRequest object
//**********************************************************************************************************/	
	Lytebox.prototype.getRequestObject = function () {
				
		var oReq = null;
		
		// First try using the standard XMLHttpRequest object, if it's supported.
		if (window.XMLHttpRequest) {
			try { oReq = new XMLHttpRequest(); } catch (e) { }
		// Otherwise, use IE's ActiveXObject, if available.
		} else if (typeof ActiveXObject != 'undefined') {
			try {
				oReq = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try { oReq = new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) { }
			}
		}
		
		return oReq;

	}; // getRequestObject()
	
//**********************************************************************************************************/
// isEmpty() - Determines if a given value is empty ('', 'null', null, or undefined). Note that a variable
//		will have the value 'null' when taking an actual null value and converting to a string.
//
//	@param	object	args	An object with properties that determines what to trim. Note that this can
//							also be used as the actual value to trim.
//
//							value		string		The value to trim
//
//	@return	boolean			true if empty, false otherwise
//**********************************************************************************************************/
	Lytebox.prototype.isEmpty = function(args) {
		
		var sValue = '';
		try {
			sValue = this.isEmpty(args.value) ? args : args.value;
		} catch(e) {
			sValue = args;
		}
		return (this.trim(sValue) == '' || sValue == 'null' || sValue == null || typeof(sValue) == 'undefined');
			
	}; // isEmpty()
	
//**********************************************************************************************************/
// stripTags() - Strips HTML tags from a string or form element
//
//	@param	object	args	An object with properties that determines what to trim. Note that this can
//							also be used as the actual value to trim.
//
//							value		string		The value to trim
//							id			string		The id of the form element to strip
//							element		object		The form element to strip
//
//	@return	string			The string minus HTML tags
//**********************************************************************************************************/
	Lytebox.prototype.stripTags = function(args) {
		
		var oElement = this.isEmpty(args.id) ? (this.isEmpty(args.element) ? null : args.element) : document.getElementById(args.id);
		if (!this.isEmpty(oElement)) {
			oElement.value = String(oElement.value).replace(/(<([^>]+)>)/ig, '');
		} else {
			var sValue = '';
			try {
				sValue = this.isEmpty(args.value) ? args : args.value;
			} catch(e) {
				sValue = args;
			}
			return (this.trim(sValue) == '[object Object]') ? '' : String(sValue).replace(/(<([^>]+)>)/ig, '');
		}
			
	}; // stripTags()
	
//**********************************************************************************************************/
// trim() - Removes white space at the beginning and end of the passed in string.
//
//	@param	object	args	An object with properties that determines what to trim. Note that this can
//							also be used as the actual value to trim.
//
//							value		string		The value to trim
//
//	@return	string			The trimmed string
//**********************************************************************************************************/
	Lytebox.prototype.trim = function(args) {
		
		var sValue = '';
		try {
			sValue = this.isEmpty(args.value) ? args : args.value;
		} catch(e) {
			sValue = args;
		}
		return String(sValue).replace(/^\s+|\s+$/g, '');
			
	}; // trim()

//**********************************************************************************************************/
// capitalize() - Capitalizes the first letter of each word.
//
//	@param	object	args	An object with properties that determines what to capitalize. Note that this can
//							also be used as the actual value to capitalize.
//
//							value		string		The value to capitalize
//
//	@return	string
//**********************************************************************************************************/
	Lytebox.prototype.capitalize = function (args) {
	
		return String(args.value ? args.value : args).replace( /(^|\s)([a-z])/g , function(m,p1,p2){return p1+p2.toUpperCase();});
		
	}; // capitalize()

//**********************************************************************************************************/
// hasClass() - Determines if a particular element has a given class associated with it.
//
//	@param	object	args	An object with properties that determine which element to query.
//
//							name		string		The class name to search for. Required.
//							id			string		The "id" of the element. Will be used by default.
//							element		object		A reference to the element. Can be used in place of id.
//
//	@return	boolean			true if found, false otherwise
//**********************************************************************************************************/
	Lytebox.prototype.hasClass = function (args) {
		
		var sClass = this.isEmpty(args.name) ? '' : args.name;
		var oElement = this.isEmpty(args.id) ? (this.isEmpty(args.element) ? null : args.element) : document.getElementById(args.id);
		return new RegExp('(\\s|^)' + sClass + '(\\s|$)').test(oElement.className);
		
	}; // hasClass()

//**********************************************************************************************************/
// addClass() - Adds a class name to a given element.
//
//	@param	object	args	An object with properties that determine which element to add the class name to.
//
//							name		string		The class name to add. Required.
//							id			string		The "id" of the element. Will be used by default.
//							element		object		A reference to the element. Can be used in place of id.
//**********************************************************************************************************/
	Lytebox.prototype.addClass = function (args) {
		
		var sClass = this.isEmpty(args.name) ? '' : args.name;
		var oElement = this.isEmpty(args.id) ? (this.isEmpty(args.element) ? null : args.element) : document.getElementById(args.id);
		
		// Since multiple class names can be passed in, we need to split the results into an array and test each value separately before adding.
		var aClasses = sClass.split(' ');
		for (var i = 0; i < aClasses.length; i++) {
			if (!this.hasClass({ element: oElement, name: aClasses[i] })) {
				oElement.className += ' ' + aClasses[i];
			}
		}
		
	}; // addClass()

//**********************************************************************************************************/
// removeClass() - Removes a class name from a given element.
//
//	@param	object	args	An object with properties that determine which element to remove the class name from.
//
//							name		string		The class name to remove. Required.
//							id			string		The "id" of the element. Will be used by default.
//							element		object		A reference to the element. Can be used in place of id.
//**********************************************************************************************************/
	Lytebox.prototype.removeClass = function (args) {
		
		var sClass = this.isEmpty(args.name) ? '' : args.name;
		var oElement = this.isEmpty(args.id) ? (this.isEmpty(args.element) ? null : args.element) : document.getElementById(args.id);
		
		// Since multiple class names can be passed in, we need to split the results into an array and test each value separately before adding.
		var aClasses = sClass.split(' ');
		for (var i = 0; i < aClasses.length; i++) {
			if (this.hasClass({ element: oElement, name: aClasses[i] })) {
				oElement.className = oElement.className.replace(new RegExp('(\\s|^)' + aClasses[i] + '(\\s|$)'), ' ').replace(/\s+/g, ' ').replace(/^\s|\s$/, '');
			}
		}
		
	}; // removeClass()
	
//***************************************************************************************************************************/
// Add Event Listeners for initLytebox, which instantiates an instance of the viewer and hooks into "lytebox" activated links
//***************************************************************************************************************************/
	if (window.addEventListener) {		// W3C
		window.addEventListener("load", initLytebox, false);
	} else if (window.attachEvent) {	// Internet Explorer 5+
		window.attachEvent("onload", initLytebox);
	} else {							// Old skool
		window.onload = function() {initLytebox();}
	}
	function initLytebox() { myLytebox = $lb = new Lytebox(true, $lb.http); }
	
	// (09/13/2011) - Initialize the object so that we have access to library functions before the document loads.
	myLytebox = $lb = new Lytebox(false);