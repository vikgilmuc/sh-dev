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
	
</head>
<body>
<div class="container"> 

	
	<div id="sticky_navigation_wrapper">
	<div id="sticky_navigation">
    <div class="row" > <!-- Reihe 1: main menu --> 
    <div class="logo col-md-1"> 
		<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/sh-logob.jpg" alt="Das Logo" />
    </div>
    
    	<div class="col-md-offset-2 navbar navbar-default navbar-collapse" id="nav" role="navigation">
    	
		<jdoc:include type="modules" name="position-0" style="html5" />
		</div>
	</div>
	</div>
	</div>

   
    <div class="row"> <!-- Reihe 3: Breadcrumb-Leiste -->
	
	
		<jdoc:include type="modules" name="position-2" style="html5" />

	</div>
	
      
    <div class="row"> <!-- Reihe 4 -->
		<div class="span0"> <jdoc:include type="modules" name="position-7" style="kinostil" titelebene="3" /> </div> <!-- Module links -->

		<?php
			if($this->params->get('farbauswahl') == "blau") $klasse="";  
			else $klasse="span7 offset2";
		?>
		<div id="maincontent" class="<?php echo $klasse ?>"> <jdoc:include type="component" style="html5" /> </div> <!-- Hauptbereich -->
			
		<?php if($this->countModules('position-8') > 0) : ?>
			<div class="span0"> <jdoc:include type="modules" name="position-8" style="html5" /> </div> <!-- Module rechts -->
		<?php endif; ?>
		
    </div>

	<div class="fusszeile"><p><small>Smiley-homes</small></p></div>
</div>

<?php $doc->addScript('templates/'.$this->template.'/js/bootstrap.min.js'); ?>

<?php $doc->addScript('templates/'.$this->template.'/js/jquery-1.10.2.min.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/jquery.easing.1.3.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/jquery.nav.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/jquery.scrollTo.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/sued3s.js'); ?>
<script>
	
	window.onload = function()
	{

	    
		slider=new Vikslider();
		
	}
	
		

		
	</script>

</body>
</html>
