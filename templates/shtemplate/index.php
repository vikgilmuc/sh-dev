<?php defined('_JEXEC') or die; 
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
//JHtml::_('bootstrap.framework');
//JHtmlBootstrap::loadCss(true, $this->direction);
$doc->addStyleSheet('templates/'.$this->template.'/css/bootstrap.min.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/base3.css');
$this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/css/template_' . $this->params->get('farbauswahl') . '.css');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head> 
	<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
</head>
<body>
<div class="container"> 

	
	<div class="navbar-fixed-top center navbar" ><!-- Reihe 1: header  id="sticky_navigation_wrapper"  --> 
   
	<div ><!-- Reihe 1: header sticky_navigation id="sticky_navigation" --> 
   
    <div class="row header" > <!-- Reihe 1: header --> 
   
     <div class="">
    <div class="logo col-md-2 col-sm-2 col-xs-2  "> <!-- Reihe 2 -->
	<!--  	<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/vectorlogosh.svg" class="img-responsive" alt="Smiley-homes Logo" />-->
    </div>
	</div>
	<div class="">
    	<nav class=" " role="navigation">
    	<div class="navbar-header" >
   		 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav">
     		 <span class="sr-only">Toggle navigation</span>
      		<span class="icon-bar"></span>
     		 <span class="icon-bar"></span>
      		<span class="icon-bar"></span>
   		 </button>
   		 
   		 </div>
    	<div class=" collapse navbar-collapse" id="nav" >
    	
		<jdoc:include type="modules" name="position-0" style="html5" />
		</div>
		</nav>
		</div>
	</div>
	</div>
	</div>

   
    <div class="row"> <!-- Reihe 3: Breadcrumb-Leiste -->
	
	
		<jdoc:include type="modules" name="position-2" style="html5" />

	</div>
	
      
    <div class="row"> <!-- Reihe 4 -->
		

		<?php
			if($this->params->get('farbauswahl') == "blau") $klasse="";  
			else $klasse="span7 offset2";
		?>
		<div id="maincontent" class="<?php echo $klasse ?>"> <jdoc:include type="component" style="html5" /> </div> <!-- Hauptbereich -->
			
		<?php if($this->countModules('position-8') > 0) : ?>
			<div class="span0"> <jdoc:include type="modules" name="position-8" style="html5" /> </div> <!-- Module rechts -->
		<?php endif; ?>
		
    </div>
    
    <div id="kontakt">
    <h1>Hier stellen wir den Kontakt</h1>
    </div>
    
<div class="span0"> <jdoc:include type="modules" name="position-7" style="kinostil" titelebene="3" /> </div> <!-- Module links -->
	<div class="fusszeile"><p><small>Smiley-homes</small></p></div>
</div>




<?php $doc->addScript('templates/'.$this->template.'/js/jquery-1.10.2.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/jquery-1.10.2.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/jquery.nav.js'); ?>

<?php $doc->addScript('templates/'.$this->template.'/js/jquery.scrollTo.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/jquery.easing.1.3.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/bootstrap.min.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/collapse.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/transition.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/sued3s.js'); ?>

  	<script>
	
	window.onload = function()
	{
	   
	    
		slider=new Vikslider();

		jQuery('.collapse').collapse();
		
		jQuery('#nav ul a:first').addClass('current');

	    
		
		
	
		$('#nav').onePageNav({
	    
	   currentClass: 'current',
	 changeHash: false,
	    scrollSpeed: 750,
	    scrollOffset: 150,
	    scrollThreshold: 0.5,
	    filter: ':not(.external)',
	    easing: 'swing',
	    begin: function() {
	       // I get fired when the animation is starting
	    },
	    end: function() {
	        //I get fired when the animation is ending
	  },
	    scrollChange: function($currentListItem) {
	       // I get fired when you enter a section and I pass the list item of the section
	    }
	});
	
	};

		
	</script>

</body>
</html>
