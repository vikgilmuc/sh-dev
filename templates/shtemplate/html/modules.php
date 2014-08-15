<?php defined('_JEXEC') or die;
function modChrome_kinostil( $module, &$params, &$attribs ) {
      echo "<div>";
      
      /* Prüfen, ob die Anzahl übergeben wurde */
      if(isset($attribs['titelebene'])) $titelebene=$attribs['titelebene'];
      else $titelebene=2; /* wenn nein, setze alle Titel einfach zwischen <h2> */
      /* Prüfen, ob die Klasse übergeben wurde. Wenn nicht, Standard-Klasse wählen */
      if(isset($attribs['klasse'])) $klasse=$attribs['klasse'];
      else $klasse='kinoklasse';
      /* Rahmen zusammenbauen: */
      /* 1. Umschließendes <div> mit entsprechender Klasse: */
      echo "<div class=\"" . $klasse . $params->get('moduleclass_sfx') . "\" >";
      /* 2. Modultitel zusammenbauen: */
      if ($module->showtitle) {
         echo "<h" . $titelebene . ">" . $module->title . "</h" . $titelebene . ">";
      }
      /* 3. Modulinhalte ausgeben: */
      echo $module->content;
      /* 4. Abschließendes </div> */
      echo "</div>";
}
?>