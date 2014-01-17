<?php
defined('_JEXEC') or die;

// Controller-Objekt erstellen:
$controller = JControllerLegacy::getInstance('Smileyhomes');

// Die gestellte Aufgabe lösen:
$controller->execute('');

// Weiterleiten, sofern der Controller dies verlangt:
$controller->redirect();
?>
