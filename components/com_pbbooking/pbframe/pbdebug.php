<?php 

/**
* pbdebug - a custom class used to help with debugging Joomla! components.  Assumes the existance of #__pbdebug_logs
*
*
* @copyright Purple Beanie
* @version 0.1
* @license GPL
* @link http://www.purplebeanie.com
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
  

class Pbdebug
{

	/**
	* records a logging message into the database
	* @param string the message to log
	* @param string the component that created the log
	*/

	public static function log_msg($msg,$component)
	{
		$db = JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		if ($config->enable_logging == 1) {
			$entry = new JObject(array('message'=>$msg,'component'=>$component,'datetime'=>date_create()->format(DATE_ATOM)));
			$db->insertObject('#__pbbooking_logs',$entry);
		}
	}


}



?>