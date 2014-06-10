<?php
defined('_JEXEC') or die;

// Einsprungspunkt für das Backend

// Controller-Objekt erstellen:
$controller = JControllerLegacy::getInstance('Kinoportal');

// Die gestellte Aufgabe lösen:
$controller->execute(JFactory::getApplication()->input->get('task'));

// Weiterleiten, sofern der Controller dies verlangt:
$controller->redirect();

?>
