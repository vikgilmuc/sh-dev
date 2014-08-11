<?php
/**
* @package		PurpleBeanie.PBBooking
* @license		GNU General Public License version 2 or later; see LICENSE.txt
* @link		http://www.purplebeanie.com
*/
 
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
  

class Pbbookinghelper
{

		
	/**
	* valid_appointment - ensures that appointment details provided during input are actually valid. 
	* loads up appropriate calendars and tests.
	*
	* @param array appointment details - requires an assoc array with cal_id, treatment_id, treatment_time, and date (as string) defined
	* @return bool returns a true or false to indiciate whether the appointment is valid or not
	*/
	
	static function valid_appointment($data)
	{
		$db = JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']));
		$treatment = $db->loadObject();
		
		$joom_config =JFactory::getConfig();
    	date_default_timezone_set($joom_config->get('offset'));	

		//load up the calendar for the nominated treatment
		$cal = new calendar();
		$cal->loadCalendarFromDbase(array($data['cal_id']));
		
		$treatment_start = date_create($data['date'],new DateTimeZone(PBBOOKING_TIMEZONE));
		$start_time = str_split($data['treatment_time'],2);
		$treatment_start->setTime((int)ltrim($start_time[0],'0'),(int)ltrim($start_time[1],'0'));
		$treatment_end = date_create($treatment_start->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$treatment_end->modify('+'.$treatment->duration.' minutes');
		if (!$cal->is_free_from_to($treatment_start,$treatment_end)) {
			//error_log ('cal is free');
			return true;
		} else {
			//error_log('cal is busy');
			return false;
		}
	}
	
	static function save_pending_event($data) 
	{
		//error_log('save_pending_event');
		$db = JFactory::getDbo();
		$config =JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));
		
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']));
		$treatment = $db->loadObject();

		$db->setQuery('select * from #__pbbooking_customfields');
		$customfields = $db->loadObjectList();
		$db->setQuery('select * from #__pbbooking_customfields where is_email = 1');
		$emailfield = $db->loadObject();
		
		$dtstart = date_create($data['date'],new DateTimeZone(PBBOOKING_TIMEZONE));
		$start_time = str_split($data['treatment_time'],2);
		$dtstart->setTime((int)ltrim($start_time[0],'0'),(int)ltrim($start_time[1],'0'));
		
		$sql = sprintf('insert into #__pbbooking_pending (date,dtstart,service,verified,cal_id,email) values ("%s","%s",%s,0,%s,"%s")',
						$db->escape(date_create($data['date'],new DateTimeZone(PBBOOKING_TIMEZONE))->format('Y-m-d')),
						$dtstart->format(DATE_ATOM),
						$db->escape($data['treatment_id']),
						$db->escape($data['cal_id']),
						$db->escape($data[$emailfield->varname]));
		//error_log($sql);
		$db->setQuery($sql);
		$result = $db->query();
		
		if ($result) {
			$pending_id = $db->insertid();
			foreach ($customfields as $field) {
				if (isset($data[$field->varname])) {
					$sql = sprintf('insert into #__pbbooking_customfields_data (customfield_id,pending_id,data) values (%s,%s,"%s")',
									$db->escape($field->id),$pending_id,$db->escape($data[$field->varname]));
					$db->setQuery($sql);
					$db->query();
				}
			}
			Pbdebug::log_msg('save_pending_event() successful for id '.$pending_id,'com_pbbooking');
			return $pending_id;
		} else {
			Pbdebug::log_msg('save_pending_event() failed with data '.json_encode($data),'com_pbbooking');
			return false;
		}
	}
	
	/**
	* email_user - sends the validation email to the user with format defined in configuration.
	* 			- New in 2.2 this method now supports all customfield tags based on |*varname*|
	*
	* @param array data the array of appt specific data
	*/	
	static function email_user($data)
	{
		Pbdebug::log_msg('email_user() sending email to user for pending event id'.$data['pending_id'],'com_pbbooking');
		$db = JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields where is_email = 1');
		$emailfield = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($data['treatment_id']));
		$service = $db->loadObject();
		$db->setQuery('select cf.varname,cfd.data from #__pbbooking_customfields cf,#__pbbooking_customfields_data cfd where cf.id = cfd.customfield_id and cfd.pending_id = '.$db->escape($data['pending_id']));
		$customfields = $db->loadObjectList();
		$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($data['pending_id']));
		$pending_appt = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape($data['cal_id']))->loadObject();
		$calendar = $db->loadObject();
		
		$mailer =JFactory::getMailer();
		$mailer_config =JFactory::getConfig();

		$recipient = $data[$emailfield->varname];
		$bcc = null;
		if ($config->bcc_admin == 1) {
			$bcc = array($mailer_config->get('mailfrom'));
			if (isset($cal->email)) $bcc[] = $cal->email;
		}
		if (JURI::base(true) != '')
			$url = str_replace(JURI::base(true).'/','',JURI::base()).JRoute::_('index.php?option=com_pbbooking&task=validate&id='.$data['pending_id'].'&email='.$data[$emailfield->varname]);
		else
			$url = preg_replace('/(.*)\/$/','$1',JURI::base()).JRoute::_('index.php?option=com_pbbooking&task=validate&id='.$data['pending_id'].'&email='.$data[$emailfield->varname]);	
		Pbdebug::log_msg('email_user() final url is '.$url,'com_pbbooking');
		$urlstring = '<a href="'.$url.'">'.JTEXT::_('COM_PBBOOKING_VALIDATE_ANCHOR_TEXT')."</a>";
		
		//send email to client to let them know what is going on
		$body = self::_prepare_email('email_body',array('service_id'=>$data['treatment_id'],'dtstart'=>$pending_appt->dtstart,'url'=>$urlstring),(array)$customfields);
		self::send_email($config->email_subject,$body,$recipient,$bcc);
	}
	
	/**
	* validate_pending - looks up a pending booking and if still valid writes to the dbase.
	*
	* @param int the id of the pending booking
	* @param string the users email address or possibly null if admin validated
	* @param string the token or possibly null if user validated
	* @returns mixed returns an int with the event_id or false for failure
	*/
	
	static function validate_pending($pending_id,$email=null,$token=null)
	{
		$db = JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		
		$joom_config =JFactory::getConfig();
    	date_default_timezone_set($joom_config->get('offset'));	

		
		$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($pending_id));
		$pending = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields where is_email =1');
		$customfields['email'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields where is_first_name =1');
		$customfields['first_name'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields where is_last_name =1');
		$customfields['last_name'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields_data where pending_id = '.$pending->id.' and customfield_id = '.$customfields['first_name']->id);
		$customdata['first_name'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields_data where pending_id = '.$pending->id.' and customfield_id = '.$customfields['last_name']->id);
		$customdata['last_name'] = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_customfields cf,#__pbbooking_customfields_data cfd where cf.id = cfd.customfield_id and cfd.pending_id = '.$db->escape($pending->id));
		$all_data = $db->loadObjectList();
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$pending->service);
		$treatment = $db->loadObject();
		$db->setQuery('select #__pbbooking_customfields_data.*,#__pbbooking_customfields.varname from #__pbbooking_customfields_data join #__pbbooking_customfields on #__pbbooking_customfields.id = #__pbbooking_customfields_data.customfield_id where pending_id = '.$db->escape($pending_id));
		$all_fields = $db->loadAssocList(); //done to support parsing to dbase later on.
		
		$dtstart = date_create($pending->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE));
		
		$validation_arr = array('date'=>$pending->date,'treatment_time'=>$dtstart->format('Hi'),'cal_id'=>$pending->cal_id,'treatment_id'=>$pending->service);
		if (self::valid_appointment($validation_arr)) {
			if ( ($config->validation != 'admin' && $email == $pending->email) || ($config->validation == 'admin' && $token == $pending->token) ) {
				$pending->verified = 1;
				$db->updateObject('#__pbbooking_pending',$pending,'id');
				
				//create the new appt
				$dtend = date_create($dtstart->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
				$dtend->modify('+ '.$treatment->duration.' minutes');
				$summary = $treatment->name.' for '.$customdata['first_name']->data.' '.$customdata['last_name']->data;
				$description  = "";
				foreach ($all_data as $data) {
					$description .= $data->fieldname.' = '.$data->data.'\n';
				}
				$appt = new JObject;
				$appt->setProperties(array('summary'=>$summary,'dtend'=>$dtend->format(DATE_ATOM),'dtstart'=>$dtstart->format(DATE_ATOM),'description'=>$description,'service_id'=>$pending->service,'email'=>$pending->email,
											'customfields_data'=>json_encode($all_fields),'deposit_paid'=>0,'amount_paid'=>0.00));
				if ($pending->cal_id == 0) {
					$db->setQuery('select * from #__pbbooking_cals where out_cal = 1');
					$out_cal = $db->loadObject();
					$appt->set('cal_id',$out_cal->id);
				} else {
					$appt->set('cal_id',$pending->cal_id);
				}
	
				//write to database
				if ($db->insertObject('#__pbbooking_events',$appt)) {
					$event_id = $db->insertid();
					self::email_admin($event_id,$pending->id);
					Pbdebug::log_msg('validate_pending() successful validation of pending_event id '.$db->escape($pending_id),'com_pbbooking');
					if ($config->validation == 'admin') {
						//the appt is admin validated so we need to let the client know that it has been validated as well....
						$body = self::_prepare_email('admin_validation_confirmed_email_body',array('service_id'=>$treatment->id,'dtstart'=>$dtstart->format(DATE_ATOM),'url'=>null),json_decode($appt->customfields_data));
						self::send_email($config->admin_validation_confirmed_email_subject,$body,$appt->email);
					}
					return $event_id;
				}
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	
	}
	
	/**
	* email admin - sends email to the administrator notifying them of a new appt in the calendar.
	*
	* @param int the id of the event in the database
	* @param int the id of the pending event in the databse
	*/
	
	static function email_admin($event_id,$pending_id)
	{
		//load up data
		Pbdebug::log_msg('email_admin: starting email of admin','com_pbbooking');
		$db = JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_config');
		$config = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_events where id = '.$db->escape($event_id));
		$event = $db->loadObject();
		$db->setQuery('select cd.data,c.fieldname from #__pbbooking_customfields c,#__pbbooking_customfields_data cd where c.id = cd.customfield_id and cd.pending_id = '.$db->escape($pending_id));
		$customdata = $db->loadObjectList();
		$db->setQuery('select * from #__pbbooking_pending where id = '.$db->escape($pending_id));
		$pending = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($pending->service));
		$treatment = $db->loadObject();
		$db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape($pending->cal_id));
		$calendar = $db->loadObject();

		//build email
		$body = JText::_('COM_PBBOOKING_ADMIN_EMAIL_BODY');
		$body .= '<br><b>'.JText::_('COM_PBBOOKING_SUCCESS_DATE').'</b> '.JHtml::_('date',date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),$config->date_format_message.' '.JText::_('COM_PBBOOKING_SUCCESS_TIME_FORMAT'));
		$body .= '<br><b>'.JText::_('COM_PBBOOKING_BOOKINGTYPE').'</b> '.$treatment->name;
		$body .= '<ul>';
		$body .= '<p>'.JText::_('COM_PBBOOKING_EDIT_LINK_MSG').' ';
		$body .= '<a href="'.JURI::root(false).'/administrator/index.php?option=com_pbbooking&controller=manage&task=edit&id='.$event_id.'">';
		$body .= JURI::root(false).'/administrator/index.php?option=com_pbbooking&controller=manage&task=edit&id='.$event_id.'</a></p>';
		foreach ($customdata as $data) {
			$body .= '<li>'.$data->fieldname.'  - '.$data->data.'</li>';
		}
		$body .- '</ul>';
		Pbdebug::log_msg('email_admin: body of email said....','com_pbbooking');
		Pbdebug::log_msg($db->escape($body),'com_pbbooking');
			
		//build recipient list
		$mailer_config =JFactory::getConfig();
		if ($calendar->email) {
			$recipient = array($calendar->email,$mailer_config->get('mailfrom'));
		} else {
			$recipient = $mailer_config->get('mailfrom');
		}

		//send_email($subject,$body,$recipient,$bcc=null)
		self::send_email(JText::_('COM_PBBOOKING_ADMIN_EMAIL_SUBJECT'),$body,$recipient);
	}
	
	/**
	* Is date a blocked day? - ideally this would be in the calendar model but it doesn't really fit there at present
	*
	* @param date the date
	* @returns bool whether the day is blocked or not
	*/
	
	public static function is_blocked_date($date)
	{
		$db = JFactory::getDbo();
		$config =JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));	

		$db->setQuery('select * from #__pbbooking_block_days');
		$block_ranges = $db->loadObjectList();
		foreach ($block_ranges as $range) {
			$block_start = date_create($range->block_start_date,new DateTimeZone(PBBOOKING_TIMEZONE))->setTime(0,0,0);
			$block_end = date_create($range->block_end_date,new DateTimeZone(PBBOOKING_TIMEZONE))->setTime(23,59,59);
			if ($date >= $block_start && $date<= $block_end) {
				return true;
			}
		}
	}

	
	/**
	* booking_for_day - returns an int with the number of bookings for nominated day
	*
	* @param datetime check_date - a datetime object containing the day to be checked
	* @return int the number of bookings for nominated date
	*/
	
	public static function booking_for_day($check_date = null)
	{
		$db = JFactory::getDbo();

		//load up all the calendars
		$db->setQuery("select id from #__pbbooking_cals");
		$cals = $db->loadColumn();
		$cal = new calendar();
		$cal->loadCalendarFromDbase($cals);	
		
		$events = $cal->number_of_bookings_for_date($check_date);
		
		return $events;		
	}
	
	/**
	* busy_times_for_day - returns an array of busy times for nominated day
	*
	* @param datetime check_date - a datetime object containing the day to be checked
	* @return array array of arrays containing busy times.  array('1000','1130') - starttime / endtime
	*/	
	
	static function busy_times_for_day($check_date = null)
	{		
		$db = JFactory::getDbo();
		$config =JFactory::getConfig();
    	date_default_timezone_set($config->get('offset'));	
		
		$bod = date_create($check_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$eod = date_create($check_date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$bod->setTime(0,0,0);
		$eod->setTime(23,59,59);
		
		//setup result arr..
		$busy_times = array();
		
		//firstly get list of all appts
		$db->setQuery('select * from #__pbbooking_events where dtstart >= "'.$bod->format(DATE_ATOM).'" and dtend <= "'.$eod->format(DATE_ATOM).'"');
		$appts = $db->loadObjectList();
		
		//loop through appts and add to busy_times array
		foreach ($appts as $appt) {
			$dtend = date_create($appt->dtend);
			$dtstart = date_create($appt->dtstart);
			$busy_times[] = array('start_hour'=>$dtstart->format('H'),'start_min'=>$dtstart->format('i'),
									'end_hour'=>$dtend->format('H'),'end_min'=>$dtend->format('i'));
		}
		
		return $busy_times;
	}



	/**
	* gets a calendar name for a specified calendar id
	* @param int the id of the calenar
	* @return string the calendar name
	* @since 2.3
	*/

	public static function get_calendar_name_for_id($cal_id)
	{
		$db = JFactory::getDbo();
		$db->setQuery('select * from #__pbbooking_cals where id = '.$db->escape($cal_id));
		$calendar = $db->loadObject();

		return $calendar->name;
	}

	/**
	* marks an event as recurring in the database and sets up the recurring parameters for it
	* @param int the id of the event to make recurring
	* @param assoc an array containing the recurrance settings - most likely a POST array
	* @return bool true or updated successfuly or false for failure
	* @since 2.3
	*/

	public static function make_recurring($id,$details)
	{
		//load up the event to manipulate
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')->from('#__pbbooking_events')->where('id = '.$db->escape($id));
		$event = $db->setQuery($query)->loadObject();

		//got the event so let's set reccurance rules
		$event->r_int = $db->escape($details['interval']);
		$event->r_freq = $db->escape($details['frequency']);
		$event->r_end = $db->escape($details['recur_end']);
		$db->updateObject('#__pbbooking_events',$event,'id');
	}



	/**
	* a function to handle sending of mail from pbbooking.  We send a few emails to let's centralise the code
	* @param string the message subject
	* @param string the message body
	* @param string the recipient
	* @param string the bcc
	* @return bool
	* @since 2.3
	*/

	public static function send_email($subject,$body,$recipient,$bcc=null)
	{
		Pbdebug::log_msg('send_email() send email to '.$recipient,'com_pbbooking');
		Pbdebug::log_msg('send_email() email body = '.$body,'com_pbbooking');

		$mailer =JFactory::getMailer();
		$config =JFactory::getConfig();
		$mailer->setSender(array($config->get('mailfrom'),$config->get('fromname')));
		
		$mailer->addRecipient($recipient);
		$mailer->addBCC($bcc);
		$mailer->setSubject($subject);
		$mailer->isHTML(true);
		
		$mailer->setBody($body);
		$mailer->Send();

		return true;

	}


	/**
	* prepares an email for sending loads the template from config, parses custom field tags
	* @param string the template to load and parse
	* @param assoc array containing appointment details
	* @param assoc array containing the custom fields and data
	* @return string email body
	* @since 2.3.1
	* @access private
	*/

	private static function _prepare_email($template,$details,$customfields)
	{
		Pbdebug::log_msg('_prepare_email() using template '.$template,'com_pbbooking');
		Pbdebug::log_msg(json_encode($details),'com_pbbooking');
		Pbdebug::log_msg(json_encode($customfields),'com_pbbooking');

		$db = JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		$service = $db->setQuery('select * from #__pbbooking_treatments where id = '.$db->escape($details['service_id']))->loadObject();

		Pbdebug::log_msg('_prepare_email() template is '.$config->$template,'com_pbbooking');
		$body = $config->$template;

		//parse custom fields tags
		foreach ($customfields as $customfield) {
			$body = preg_replace('/\|\*'.$customfield->varname.'\*\|/',$customfield->data,$body);
		}
		
		//append service details if required
		$booking_details = '';
		$booking_details .= "<p><table>";
		$booking_details .= "<tr><th>".JTEXT::_('COM_PBBOOKING_SUCCESS_DATE')."</th><td>".JHtml::_('date',date_create($details['dtstart'],new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),$config->date_format_message);
		$booking_details .= "<tr><th>".JTEXT::_('COM_PBBOOKING_SUCCESS_TIME')."</th><td>".JHtml::_('date',date_create($details['dtstart'],new DateTimeZone(PBBOOKING_TIMEZONE))->format(DATE_ATOM),JText::_('COM_PBBOOKING_SUCCESS_TIME_FORMAT'))."</td></tr>";
		$booking_details .= "<tr><th>".JTEXT::_('COM_PBBOOKING_BOOKINGTYPE')."</th><td>".$service->name."</td></tr></table></p>";
		$body = preg_replace('/\|\*booking_details\*\|/',$booking_details,$body);

		//append url string if we have it.....
		$body = preg_replace('/\|\*URL\*\|/',$details['url'],$body);

		Pbdebug::log_msg('_prepare_email() template after all replacements '.$body,'com_pbbooking');

		//return completed string
		return $body;
	}

	/** 
	* free appointments for day - returns whether there are any free appointment times for the given day bail as soon as possbile cause this method is called A LOT....
	* @param datetime 
	* @return bool
	* @since 2.0.6
	* @access public
	*/

	public static function free_appointments_for_day($date)
	{
		$db = JFactory::getDbo();
		$config = $db->setQuery('select * from #__pbbooking_config')->loadObject();
		$cals = $db->setQuery('select * from #__pbbooking_cals')->loadObjectList();
		
		$dt_start = date_create($date->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
		$dt_end = date_create($date->format(DATE_ATOM), new DateTimeZone(PBBOOKING_TIMEZONE));

		$opening_hours = json_decode($config->trading_hours,true);
		$check_cals = array();
		foreach ($cals as $i=>$cal) {
			$check_cals[$i] = new calendar();
			$check_cals[$i]->loadCalendarFromDbase(array($cal->id));
		}

		//check maybe it's a no trade day?
		if($opening_hours[$date->format('w')]['status']=="closed") { 
			return false;
		}

		$open_times_arr = str_split($opening_hours[$date->format('w')]['open_time'],2);
		$close_times_arr = str_split($opening_hours[$date->format('w')]['close_time'],2);
		$dt_start->setTime($open_times_arr[0],$open_times_arr[1]);
		$dt_end->setTime($close_times_arr[0],$close_times_arr[1]);

		$times_free = false;
		while (($dt_start< $dt_end) && (!$times_free)) {
			$dt_slot_end = date_create($dt_start->format(DATE_ATOM),new DateTimeZone(PBBOOKING_TIMEZONE));
			$dt_slot_end->modify('+ '.$config->time_increment.' minutes');
			foreach ($check_cals as $cal) {
				if (!$cal->is_free_from_to($dt_start,$dt_slot_end)) {
					$times_free = true;
					return true;
				}
			}
			$dt_start->modify('+ '.$config->time_increment.' minutes');
		}

	}

}