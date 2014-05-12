<?php
defined('_JEXEC') or die;

class SmileyhomesViewWohnungbearbeitung extends JViewLegacy
{
	// Variablen zur Speicherung des zu bearbeitenden Wohnungs und des dafür zuständigen Formulars:
	protected $wohnung;
	protected $form;
	
	public function display($tpl = null)
	{
		// Daten holen und merken:
		$this->wohnung = $this->get('Item');
		$this->form = $this->get('Form');
		
		// Werkzeugleiste einrichten:
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$isNew = ($this->wohnung->id == 0);
		JToolBarHelper::title($isNew ? JText::_('COM_SMILEYHOMES_FILMBEARBEITUNG_TITEL_NEW') : JText::_('COM_SMILEYHOMES_FILMBEARBEITUNG_TITEL_EDIT'));
		JToolBarHelper::save('wohnungbearbeitung.save');
		JToolBarHelper::cancel('wohnungbearbeitung.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
		
		// Layout aktivieren:
		parent::display($tpl);
	}
}
?>
