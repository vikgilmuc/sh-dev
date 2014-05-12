<?php defined('_JEXEC') or die; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head> 
	<jdoc:include type="head" />
</head>
<body>

<div> <!-- Reihe 1: Suche --> 
	<jdoc:include type="modules" name="position-0" />
</div>

<div> <!-- Reihe 2 -->
	<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/kinoportal_logo.png" alt="Das Logo" />
</div>

<div> <!-- Reihe 3: Breadcrumb-Leiste -->
	<jdoc:include type="modules" name="position-2" />
</div>
      
<div> <!-- Reihe 4 -->
	<div> <jdoc:include type="modules" name="position-7" /> </div> <!-- Module links -->
	<div> <jdoc:include type="component" /> </div> <!-- Hauptbereich -->
	<div> <jdoc:include type="modules" name="position-8" /> </div> <!-- Module rechts -->
</div>

<div><p><small>Dies ist später die Fußzeile.</small></p></div>
</body>
</html>
