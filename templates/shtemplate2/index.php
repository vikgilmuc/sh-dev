<?php defined('_JEXEC') or die; 
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
//JHtml::_('bootstrap.framework');
//JHtmlBootstrap::loadCss(true, $this->direction);
$doc->addStyleSheet('templates/'.$this->template.'/css/bootstrap.min.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/base3.css');
$doc->addStyleSheet('templates/'.$this->template.'/fancybox/jquery.fancybox.css'); 
//$this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/css/template.css');
$this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/css/template_' . $this->params->get('farbauswahl') . '.css');

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
jQuery(document).ready(function(){
    var hiddenDiv = document.getElementById("login");
    jQuery('#login').hover(function(){ 
        hiddenDiv.style.display =  "block"},function(){ 
            hiddenDiv.style.display =  "none"} );
    jQuery('.item-121').hover( function(){ 
        hiddenDiv.style.display =  "block"},function(){ 
            hiddenDiv.style.display =  "none"}  );
      });
        
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47727230-1', 'smiley-homes.at');
  ga('send', 'pageview');


   



</script>	
</head>
<body>
<div id="mainmenu" class="clearfix nav-fixed-bottom" style="background-color: rgba(255,255,255,0.9)"> 
   <jdoc:include type="modules" name="position-0"  />   </div> 
   
<div class="container-fluid" >
 
 <!--  
  
    <div id="menuspace"class=" row clearfix  ">  
		
    </div>

<div class="">
    <div class="logo row clearfix  ">  
		<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/smiley-homes-mit-schrift.png" class="img-responsive" alt="Smiley-homes Logo" />
    </div>
</div> -->
    
      
    <div> <!-- Reihe 4 -->
		
		 <jdoc:include type="component" style="html5" />  <!-- Hauptbereich -->
		
		
		
    </div>
    
    
   
<div id="login"> <jdoc:include type="modules" name="position-6" style="kinostil" titelebene="3" /> </div> 
	</div>   
   
<div class="fusszeile"> <jdoc:include type="modules" name="position-7" style="kinostil" titelebene="3" /> </div> 
	</div>




<?php $doc->addScript('templates/'.$this->template.'/js/jquery.nav.js'); ?>

<?php $doc->addScript('templates/'.$this->template.'/js/jquery.scrollTo.js'); ?>

<?php $doc->addScript('templates/'.$this->template.'/js/bootstrap.min.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/collapse.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/transition.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/fancybox/jquery.fancybox.js'); ?>
<?php $doc->addScript('templates/'.$this->template.'/js/sued3s.js'); ?>

	  

	
</body>
</html>
