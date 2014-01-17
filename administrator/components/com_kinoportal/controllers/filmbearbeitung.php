<?php
defined('_JEXEC') or die;

class KinoportalControllerFilmbearbeitung extends JControllerForm
{
	public function __construct($config = array())
	{
		$this->view_list = 'filmliste';
		parent::__construct($config);
	}
}
?>
