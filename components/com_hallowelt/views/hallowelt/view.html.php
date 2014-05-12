<?php
// Erlaube Zugriff nur von Joomla! aus:
defined('_JEXEC') or die;

// Die View-Klasse (von JViewLegacy abgeleitet):
class HalloweltViewHallowelt extends JViewLegacy
{
	// Variable zur Speicherung des anzuzeigenden Textes:
	protected $gruesse;

	// Ausgabefunktion:
	function display($tpl = null)
	{
		// Ausgabe des Model in $gruesse merken:
		$this->gruesse = $this->get('Hallo');

		// Abschließend display() der Basisklasse aufrufen:
		parent::display($tpl);
	}
}
?>