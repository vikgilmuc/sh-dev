<?php
defined('_JEXEC') or die;

class KinoportalViewKinoportal extends JViewLegacy
{
	// Variable zur Speicherung aller Filme:
	protected $allefilme;

	function display($tpl = null)
	{
		// Hole Filme vom Model, und speichere sie in $allefilme:
		$this->allefilme = $this->get('Filme');
		
		// Layout aktivieren und alles ausgeben:
		parent::display($tpl);
	}
}
?>
