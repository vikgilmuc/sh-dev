<?php
defined('_JEXEC') or die;

class SmileyhomesControllerWohnungliste extends JControllerAdmin
{
	public function getModel($name = 'Wohnungbearbeitung', $prefix = 'SmileyhomesModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
?>
