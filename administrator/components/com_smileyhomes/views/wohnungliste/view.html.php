<?php
defined('_JEXEC') or die;

class SmileyhomesViewWohnungliste extends JViewLegacy
{
	// Variablen zur Speicherung der anzuzeigenden Wohnungen und der Seitenzahl:
	protected $wohnungen;
	protected $seitenzahl;
	
	function display($tpl = null)
	{
		// Hole Daten aus dem Model:
		$this->wohnungen = $this->get('Items');
		$this->seiten = $this->get('Pagination');
		
		JToolbarHelper::title(JText::_('COM_SMILEYHOMES_WOHNUNGLISTE_TITEL'));
		JToolBarHelper::addNew('wohnungbearbeitung.add');
		JToolBarHelper::editList('wohnungbearbeitung.edit');
		JToolBarHelper::deleteList(JText::_('COM_SMILEYHOMES_WOHNUNGLISTE_LOESCHEN_FRAGE'), 'wohnungliste.delete');

		// Layout aktivieren und anzeigen:
		parent::display($tpl);
	}
}
?>
