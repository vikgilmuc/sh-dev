<?php
defined('_JEXEC') or die;

class SmileyhomesControllerWohnungbearbeitung extends JControllerForm
{
	public function __construct($config = array())
	{
		$this->view_list = 'wohnungliste';
		parent::__construct($config);
	}
}
?>
