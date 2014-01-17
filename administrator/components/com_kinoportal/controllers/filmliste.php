<?php
defined('_JEXEC') or die;

class KinoportalControllerFilmliste extends JControllerAdmin
{
	public function getModel($name = 'Filmbearbeitung', $prefix = 'KinoportalModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
?>
