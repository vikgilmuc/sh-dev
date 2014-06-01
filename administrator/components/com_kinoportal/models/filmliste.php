<?php
defined('_JEXEC') or die;

class KinoportalModelFilmliste extends JModelList
{
	protected function getListQuery()
	{
		// Erstelle ein neues Query-Objekt:
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	
		// Hole alle Filme:
		$query->select('*');
	
		// aus der Tabelle:
		$query->from('#__filme');
	
		// und liefere sie zurück
		return $query;
	}
}

?>
