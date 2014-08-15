<?php
defined('_JEXEC') or die;

class modSmileyhomesHelper
{
	static function holeAlleWohnungen($params)
	{
		//Zugriff auf die Datenbank holen:
		$datenbank = JFactory::getDbo();
		
		$inhaltvonanzahl = $params->get( 'anzahl', 10 );
		
		// Anfrage basteln:
		$query = $datenbank->getQuery(true);
		$query->select('*');
		$query->from('#__wohnungen');
		
		// Filme holen:
		$datenbank->setQuery($query, 0, $inhaltvonanzahl);
		$allewohnungen = $datenbank->loadObjectList();
		
		return $allewohnungen;
	}
}
?>
