<?php
defined('_JEXEC') or die;
 
// Hallowelt-Controller-Objekt erstellen:
$controller = JControllerLegacy::getInstance('Hallowelt');
 
// Die gestellte Aufgabe lösen:
$controller->execute('');

// Weiterleiten, sofern der Controller dies verlangt:
$controller->redirect();
?>
