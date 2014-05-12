<?php
defined('_JEXEC') or die;

class KinoportalModelFilmbearbeitung extends JModelAdmin
{
	public function getTable($type = 'Kinoportal', $prefix = 'KinoportalTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
	
		// Lade das Formular:
		$form = $this->loadForm('com_kinoportal.filmbearbeitung', 'filmbearbeitung', array('control' => 'jform', 'load_data' => $loadData));
		
		// Prüfe, ob es leer ist ...
		if (empty($form))
		{
			return false;
		}
		
		// ... und liefere es zurück:
		return $form;
	}
	
	protected function loadFormData()
	{
		return $this->getItem();
	}
}

?>
