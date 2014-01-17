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
</head>
<body>
<div class="container">
   
 <div class="">
    <div class="logo row clearfix  "> <!-- Reihe 2 -->
		<img  src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/smiley-homes-mit-schrift.png" alt="Smiley-homes Logo" />
    </div>
</div>
    
      
    <div class="row col-md-10 col-md-offset-1 clearfix"> <!-- Reihe 4 -->
		
		 <jdoc:include type="component" style="html5" />  <!-- Hauptbereich -->
		
		
		
    </div>
    
    <p> just a test </p>
   
    <div class="suche"> 
		<jdoc:include type="modules" name="position-0" style="html5" />
	</div>
    

	<div class="fusszeile"> <jdoc:include type="modules" name="position-7" style="kinostil" titelebene="3" /> </div> <!-- Module links -->
	
</div>
</body>
</html>
