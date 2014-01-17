<?php defined('_JEXEC') or die; ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head> 
	<jdoc:include type="head" />
</head>
<body>
   <p>Folgender Fehler ist aufgetreten:</p>
   <p>Nummer: <?php echo $this->error->getCode(); ?> </p>
   <p>Grund: <?php echo $this->error->getMessage(); ?> </p>
   <p><a href="<?php echo $this->baseurl; ?>/index.php">Zurück zur Startseite</a></p>
</body>
</html>