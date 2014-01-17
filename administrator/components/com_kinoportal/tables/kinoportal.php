<?php
defined('_JEXEC') or die;
 
class KinoportalTableKinoportal extends JTable
{
	function __construct(&$db)
	{
		parent::__construct('#__filme', 'id', $db);
	}
}
?>
