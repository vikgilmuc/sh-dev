<?php
// Sicherheitsprüfung: Wird die Klasse von Joomla! verwendet?
defined('_JEXEC') or die;

// Die Model-Klasse (von JModelLegacy abgeleitet):
class KinoportalModelKinoportal extends JModelLegacy
{
	// Gib auf Anfrage die Filme aus:
	function getFilme()
	{
		// Zugriff auf die Datenbank holen:
		$datenbank = JFactory::getDbo();
		
		// Datenbankabfrage zusammenstellen:
		$query = $datenbank->getQuery(true);
		$query->from('#__filme');
		$query->select('*');
		$query->order('kosten');

		// Anfrage ausführen und alle Filme entgegennehmen:
		$datenbank->setQuery((string)$query);
		$allefilme = $datenbank->loadObjectList();
		return $allefilme;
	}
}
?>
