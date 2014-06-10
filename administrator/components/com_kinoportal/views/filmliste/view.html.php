<?php
defined('_JEXEC') or die;

class KinoportalViewFilmliste extends JViewLegacy
{
	// Variablen zur Speicherung der anzuzeigenden Filme und der Seitenzahl:
	protected $filme;
	protected $seitenzahl;
	
	function display($tpl = null)
	{
		// Hole Daten aus dem Model:
		$this->filme = $this->get('Items');
		$this->seiten = $this->get('Pagination');
		
		JToolbarHelper::title(JText::_('COM_KINOPORTAL_FILMLISTE_TITEL'));
		JToolBarHelper::addNew('filmbearbeitung.add');
		JToolBarHelper::editList('filmbearbeitung.edit');
		JToolBarHelper::deleteList(JText::_('COM_KINOPORTAL_FILMLISTE_LOESCHEN_FRAGE'), 'filmliste.delete');

		// Layout aktivieren und anzeigen:
		parent::display($tpl);
	}
}
?>
