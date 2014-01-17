<?php
defined('_JEXEC') or die;

class KinoportalViewFilmbearbeitung extends JViewLegacy
{
	// Variablen zur Speicherung des zu bearbeitenden Films und des dafür zuständigen Formulars:
	protected $film;
	protected $form;
	
	public function display($tpl = null)
	{
		// Daten holen und merken:
		$this->film = $this->get('Item');
		$this->form = $this->get('Form');
		
		// Werkzeugleiste einrichten:
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$isNew = ($this->film->id == 0);
		JToolBarHelper::title($isNew ? JText::_('COM_KINOPORTAL_FILMBEARBEITUNG_TITEL_NEW') : JText::_('COM_KINOPORTAL_FILMBEARBEITUNG_TITEL_EDIT'));
		JToolBarHelper::save('filmbearbeitung.save');
		JToolBarHelper::cancel('filmbearbeitung.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
		
		// Layout aktivieren:
		parent::display($tpl);
	}
}
?>
