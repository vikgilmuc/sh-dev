<?php
defined('_JEXEC') or die;

class SmileyhomesViewSmileyhomes extends JViewLegacy
{
	// Variable zur Speicherung aller Filme:
	protected $allewohnungen;

	function display($tpl = null)
	{
		// Hole Filme vom Model, und speichere sie in $allefilme:
		$this->allewohnungen = $this->get('Wohnungen');
		
		// Layout aktivieren und alles ausgeben:
		parent::display($tpl);
	}
}
?>
