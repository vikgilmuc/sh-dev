<?php
defined('_JEXEC') or die;

class SmileyhomesModelWohnungliste extends JModelList
{
	protected function getListQuery()
	{
		// Erstelle ein neues Query-Objekt:
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
	
		// Hole alle Wohnungen:
		$query->select('*');
	
		// aus der Tabelle:
		$query->from('#__wohnungen');
	
		// und liefere sie zurück
		return $query;
	}
}

?>
