<?php
defined('_JEXEC') or die;
 
class SmileyhomesTableSmileyhomes extends JTable
{
	function __construct(&$db)
	{
		parent::__construct('#__wohnungen', 'id', $db);
	}
}
?>
