<?php defined('_JEXEC') or die; 
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
//JHtml::_('bootstrap.framework');
//JHtmlBootstrap::loadCss(true, $this->direction);
$doc->addStyleSheet('templates/'.$this->template.'/css/bootstrap.min.css');

$doc->addStyleSheet('templates/'.$this->template.'/fancybox/jquery.fancybox.css'); 
$this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/css/template.css');
$this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/css/template_' . $this->params->get('farbauswahl') . '.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/base3.css');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head> 
	<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="templates/shtemplate2/js/html5shiv.js"></script>
  <script src="templates/shtemplate2/js/respond.min.js"></script>
  <script src="templates/shtemplate2/js/css3-mediaqueries.js"></script>
  <link rel="stylesheet" type="text/css" href="templates/shtemplate2/css/ie.css">
<![endif]-->
	<script>
	
	

      
jQuery('.nav a').on('click', function(){
    jQuery(".navbar-toggle").click();


});

 jQuery('a[rel="imagelightbox-sigplus_9370d1d0beb1d93c2d1d37a5294aa4a7"]').imageLightbox(
{ });
     
    
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47727230-1', 'smiley-homes.at');
  ga('send', 'pageview');


  

</script>	


</head>
<body>
   
    <!-- Reihe 4 -->
   
    <nav id="mainmenu" class="clearfix nav-fixed-bottom"  role="navigation"> 
        <div id="padre" class="container-fluid">
        <div class="navbar-header">
         <button id="buto" type="button" style="background-color: #FFFFFF"  class="btn navbar-toggle" data-toggle="collapse" data-parent="#padre" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <a class="navbar-brand" href="#"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse" style="background-color: rgba(255,255,255,0.8)" >
            <jdoc:include type="modules" name="position-0"  />   
        </div>  
        </div>
   </nav>  
    <div class="container-fluid" >
 
   
        <div> <!-- Reihe 4 -->
    				 <jdoc:include type="component" style="html5" />  <!-- Hauptbereich -->
    	
        </div>
		
		</div>
        
    <aside role="complementary">
	

	   
		<div id="padre2" class="container-fluid col-xs-offset-1 col-xs-3">
            
			<button id="button-login" type="button" class="btn 
			
								<?php 
					$user = JFactory::getUser();
					$status = $user->guest;

					if($status == 1){

					}
					else
					{ 
					echo("nodisplay");
					}   
					?>
 
					
" style="background-color: white" data-toggle="collapse" data-target="#demo">

<a href="#" class="svg">
			  <img id="login-img" style="display:block" src="./images/login.png" /></a>
			</button>

					<div id="demo" class="collapse 
									<?php 
						$user = JFactory::getUser();
						$status = $user->guest;

						if($status == 1){

						}
						else
						{ 
						echo("in");
						}   
						?>
			"><jdoc:include type="modules" name="position-6" style="kinostil" titelebene="3" /> </div>
                
              
		</div>
	
	
        <div class="navbar-fixed-top  col-xs-offset-10 col-xs-3">
        <div class="btn-group panel " >
                    <ul class="socials panel-body">
                        <li><a href="http://www.facebook.com/pages/Smiley-Homes-Gmbh/495349477245628" target="_blank"><i class="sprite fb">Finde uns auf Facebook</i></a></li>
                        <li><a href="https://twitter.com/smiley_homes" target="_blank"><i class="sprite tw">Folge uns auf Twitter</i></a></li>
                        <li><a href="https://plus.google.com/110379789250295232725/about?hl=de" target="_blank"><i class="sprite go">Teile mit uns auf Google+</i></a></li>
                                            </ul>
                                 </div>           </div>
    </aside>
   
    
       
    <div class="fusszeile"> <jdoc:include type="modules" name="position-7" style="kinostil" titelebene="3" /> </div> 
    	</div>
    
    
    
    <?php $doc->addScript('templates/'.$this->template.'/js/collapse.js'); ?>
    <?php $doc->addScript('templates/'.$this->template.'/js/transition.js'); ?>
    <?php $doc->addScript('templates/'.$this->template.'/js/jquery.nav.js'); ?>
    <?php $doc->addScript('templates/'.$this->template.'/js/jquery.scrollTo.js'); ?>
    <?php $doc->addScript('templates/'.$this->template.'/fancybox/jquery.fancybox.js'); ?>
	<?php $doc->addScript('plugins/content/sigplus/engines/imagelightbox/js/imagelightbox.js'); ?>
    <?php $doc->addScript('templates/'.$this->template.'/js/sued3s.js'); ?>
    
    <script>
        var myApp;
             myApp = myApp || (function () {
             var pleaseWaitDiv = jQuery('<div class="modal fade" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h1>Processing...</h1></div><div class="modal-body"><div class="progress progress-striped active"><div class="progress-bar progress-bar-striped active" style="width: 100%;"></div></div></div></div></div></div>');
            return {
                showPleaseWait: function() {
                    pleaseWaitDiv.modal('show');
                },
                hidePleaseWait: function () {
                    pleaseWaitDiv.modal('hide');
                },
        
            };
            })();
            
        jQuery(document).ready(function(){
            
            
            jQuery("#contact").submit(function(e) {
            
         var url1= "/modules/mod_shformmodule/mod_shformmodule.php"; // the script where you handle the form input.

        jQuery.ajax({
           type: "POST",
           url: url1,
           data: jQuery("#contact").serialize(), // serializes the form's elements.
           success: function(data)
           {
               myApp.hidePleaseWait();
               jQuery('#contact').html(data) // show response from the php script.
           }
         });
         
            myApp.showPleaseWait();
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
  
 
});
    </script>    

</body>
</html>
