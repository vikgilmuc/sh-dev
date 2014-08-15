<?php
// Sicherheitsprüfung: Wird die Klasse von Joomla! verwendet?
defined('_JEXEC') or die;

// Die Model-Klasse (von JModelLegacy abgeleitet):
class SmileyhomesModelSmileyhomes extends JModelLegacy
{
	// Gib auf Anfrage die Filme aus:
	function getWohnungen()
	{
		// Zugriff auf die Datenbank holen:
		$datenbank = JFactory::getDbo();
		
		// Datenbankabfrage zusammenstellen:
		$query = $datenbank->getQuery(true);
		$query->from('#__wohnungen');
		$query->select('*');
		$query->order('id');

		// Anfrage ausführen und alle Filme entgegennehmen:
		$datenbank->setQuery((string)$query);
		$allewohnungen = $datenbank->loadObjectList();
		return $allewohnungen;
	}
}
?>
