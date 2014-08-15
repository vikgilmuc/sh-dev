<?php

/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or la<ter; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/

// No direct access
 
defined('_JEXEC') or die('Restricted access'); 

jimport('joomla.library.crypt');




class Pbgcal 
{

	/** 
	* loads the relevant gdata libraries and changes the include path
	* @access public
	* @since 2.4.5
	*/

	public static function load_gdata_libraries()
	{
		$db = JFactory::getDbo();
		//$cal = $db->setQuery('select * from #__pbbooking_cals where id = '.(int)$appt->cal_id)->loadObject();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();

		Pbdebug::log_msg('Pbgcal::load_gdata_libraries() config->path_to_zend = '.$config->path_to_zend,'com_pbbooking');	
		set_include_path(get_include_path() . PATH_SEPARATOR . $config->path_to_zend);
		Pbdebug::log_msg('Pbgcal::load_gdata_libraries() include path set to '.get_include_path(),'com_pbbooking');
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass('Zend_Gdata');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Gdata_HttpClient');
		Zend_Loader::loadClass('Zend_Gdata_Calendar');
		Zend_Loader::loadClass('Zend_Gdata_Query');
		Pbdebug::log_msg('Pbgcal::load_gdata_libraries() finished loading all zend required classes','com_pbbooking');
	}

	/**
	* performs the client login and returns a Zend_Gdata_Calendar
	* @access public
	* @since 2.4.5
	* @param string the username
	* @param string the password
	* @return object a Zend_Gdata_Calendar object
	*/

	public static function do_client_login($username,$password)
	{
		//first things first - decrpyt the password.
		$p_key = JApplication::getHash($username);
		$key = new JCryptKey('simple',$p_key,$p_key);
		$crypt = new JCrypt(new JCryptCipherSimple,$key,$key);
		$password = $crypt->decrypt($password);

		Pbdebug::log_msg('Pbgcal::do_client_login() trying connection to google cal with credentials '.$username.' and '.$password,'com_pbbooking');
		$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
		try {
	  		$client = Zend_Gdata_ClientLogin::getHttpClient($username, $password, $service);
		}catch(Zend_Gdata_App_Exception $ex) {
		    return false;
		}
		$gdataCal = new Zend_Gdata_Calendar($client);
		Pbdebug::log_msg('Pbgcal::do_client_login() finished doing google cal login','com_pbbooking');	

		return $gdataCal;
	}

	/**
	* deletes an event from the gcal database
	* @access public
	* @since 2.4.5.1
	* @param string the event id
	* @param string the username
	* @param string the password
	* @return bool
	*/

	public static function delete_event($gcal_id,$username,$password)
	{
		Pbdebug::log_msg('Pbgcal::delete_event() '.$gcal_id,'com_pbbooking');
		self::load_gdata_libraries();

		$gDataCal = self::do_client_login($username,$password);

		//try to load the event...
		try {
			$event =$gDataCal->getCalendarEventEntry($gcal_id);
			Pbdebug::log_msg('Pbgcal::delete_event() got back event '.json_encode($event),'com_pbbooking');
			if ($event)
				$event->delete();
			else
				Pbdebug::log_msg('Pbgcal::delete_event() event with id '.$gcal_id.' does not exist.'.'com_pbbooking');
		} catch (Zend_Gdata_App_Exception $e) {
			Pbdebug::log_msg('Pbgcal::delete_event() problem retreiving event','com_pbbooking');
		}

		return true;

	}



}


?>