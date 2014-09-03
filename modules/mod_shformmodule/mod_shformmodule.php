<?php /* Erlaube Zugriff nur von Joomla! aus: */
defined('_JEXEC') or die('Restricted access');
require_once __DIR__ . '/helper.php';
$doc = JFactory::getDocument();

require(JModuleHelper::getLayoutPath('mod_shformmodule'));
?>

<?php
if (!isset($_POST["from"])) {
  ?>
  
  <?php 
} else {    // the user has submitted the form
  // Check if the "from" input field is filled out
  if (isset($_POST["from"])) {
  
   // mail("victor@victorgil.name",$subject,$message,"From: $from\n");
 echo ' <div class="alert alert-success" role="alert"><h3>Ihre Meldung:</h3><br>' ;
echo "from:".$_POST['from'];
echo "<br>";  
echo "subject:".$_POST['subject'];
echo "<br>";  
echo "message:".$_POST['message'];

 echo "<br>"; 
  mail("victor@victorgil.name",$_POST['subject'], $_POST['from']+$_POST['message'] );
    
    echo " wurde gesendet. Danke</div>";
  }
}
?>


