<?php
// Sicherheitsprüfung: Wird die Klasse von Joomla! verwendet?
defined('_JEXEC') or die;

// Die Model-Klasse (von JModelLegacy abgeleitet):
class HalloweltModelHallowelt extends JModelLegacy
{
	
	function getHallo()
	{
		return 'Hallo Welt!';
	}
}
?>
