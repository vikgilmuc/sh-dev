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
	
	<script>
jQuery(document).ready(function(){
    var hiddenDiv = document.getElementById("login");
    jQuery('#login').hover(function(){ 
        hiddenDiv.style.display =  "block"},function(){ 
            hiddenDiv.style.display =  "none"} );
    jQuery('.item-121').hover( function(){ 
        hiddenDiv.style.display =  "block"},function(){ 
            hiddenDiv.style.display =  "none"}  );
      
        
   


})
</script>	
</head>
<body>

<div class="container">
  <div class="head-menu clearfix navbar-fixed-top" style="background-color: rgba(255,255,255,0.4)"> <div class="container">
   <jdoc:include type="modules" name="position-0"  /> </div>   </div>
	
 
 
 
  
    <div id="menuspace"class=" row clearfix  ">  
		
    </div>

 <!--  <div class="">
    <div class="logo row clearfix  ">  
		<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/smiley-homes-mit-schrift.png" class="img-responsive" alt="Smiley-homes Logo" />
    </div>
</div> -->
    
      
    <div class="row col-md10 col-md-offset-1 clearfix"> <!-- Reihe 4 -->
		
		 <jdoc:include type="component" style="html5" />  <!-- Hauptbereich -->
		
		
		
    </div>
    
    
   
<div id="login"> <jdoc:include type="modules" name="position-6" style="kinostil" titelebene="3" /> </div> 
	</div>   
   
<div class="fusszeile"> <jdoc:include type="modules" name="position-7" style="kinostil" titelebene="3" /> </div> 
	</div>


<?php $doc->addScript('templates/'.$this->template.'/js/jquery-1.10.2.js'); ?>

<?php $doc->addScript('templates/'.$this->template.'/js/jquery.nav.js'); ?>

<?php $doc->addScript('templates/'.$this->template.'/js/jquery.scrollTo.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/jquery.easing.1.3.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/bootstrap.min.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/collapse.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/transition.js'); ?>

	  

	
</body>
</html>
