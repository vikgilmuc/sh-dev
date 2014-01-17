<?php defined('_JEXEC') or die; 

JHtml::_('bootstrap.framework');
JHtmlBootstrap::loadCss(true, $this->direction);
$this->addStyleSheet(JURI::base() . 'templates/' . $this->template . '/css/template.css');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head> 
	<jdoc:include type="head" />
</head>
<body>
<div class="container">
    <div class="suche"> <!-- Reihe 1: Suche --> 
		<jdoc:include type="modules" name="position-0" style="html5" />
	</div>

    <div class="logo"> <!-- Reihe 2 -->
		<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/kinoportal_logo.png" alt="Das Logo" />
    </div>

    <div class="breadcrumbs"> <!-- Reihe 3: Breadcrumb-Leiste -->
		<jdoc:include type="modules" name="position-2" style="html5" />
	</div>
      
    <div class="row"> <!-- Reihe 4 -->
		<div class="span3"> <jdoc:include type="modules" name="position-7" style="html5" /> </div> <!-- Module links -->
		<div class="span6"> <jdoc:include type="component" style="html5" /> </div> <!-- Hauptbereich -->
		<div class="span3"> <jdoc:include type="modules" name="position-8" style="html5" /> </div> <!-- Module rechts -->
    </div>

	<div class="fusszeile"><p><small>(C) Kinoportal</small></p></div>
</div>
</body>
</html>
