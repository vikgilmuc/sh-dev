<?php

//this just loads up the international options to be used for the datetime picker and outputs them to the current document. which can then be included in the 

/*

dateFormat
dayNames
dayNamesMin
dayNamesShort
nextText
prevText
currentText
closeText
isRTL
monthNames
monthNamesShort

*/
	
	$config = JFactory::getConfig();
	$lang = JLanguage::getInstance($config->get('language'));
	$db = JFactory::getDbo();
	$pbbConfig = $db->setQuery('select * from #__pbbooking_config')->loadObject();

	$dtLocalisation = array();
	
	//dateFormat
	$dtLocalisation['dateFormat'] = 'yy-mm-dd';

	//dayNames, dayNamesMin, dayNamesShort
	$dtLocalisation['dayNames'] = array();
	$dtLocalisation['dayNamesMin'] = array();
	$dtLocalisation['dayNamesShort'] = array();

	$bow = date_create("this sunday",new DateTimeZone($config->get('offset')));
	for($i=0;$i<7;$i++){
		$dtLocalisation['dayNames'][] = Jhtml::_('date',$bow->format(DATE_ATOM),'l');
		$dtLocalisation['dayNamesMin'][] = Jhtml::_('date',$bow->format(DATE_ATOM),'D');
		$dtLocalisation['dayNamesShort'][] = Jhtml::_('date',$bow->format(DATE_ATOM),'D');
		$bow->modify('+1 day');
	}

	//nextText,prevText,currentText,closeText
	foreach (array('nextText','prevText','currentText','closeText') as $str) {
		$dtLocalisation[$str] = JText::_('COM_PBBOOKING_DATEPICKER_'.strtoupper($str));
	}

	//isRTL
	$dtLocalisation['isRTL'] = ($lang->isRTL()) ? true : 0;

	//monthNames, monthNamesShort
	$dtLocalisation['monthNames'] = array();
	$dtLocalisation['monthNamesShort'] = array();
	$boy = date_create("first day of january",new DateTimeZone($config->get('offset')));
	for ($i=0;$i<12;$i++) {
		$dtLocalisation['monthNames'][] = JHtml::_('date',$boy->format(DATE_ATOM),'F');
		$dtLocalisation['monthNamesShort'][] = JHtml::_('date',$boy->format(DATE_ATOM),'M');
		$boy->modify('+1 month');
	}

	/* not let's do some localisation for the dtpicker */

	$dtPickerLocalisation = array();

	//first set the strings
	foreach (array('timeOnlyTitle','timeText','hourText','minuteText','millisecText','timezoneText','currentText','closeText') as $str) {
		$dtPickerLocalisation[$str] = JText::_('COM_PBBOOKING_TIMEPICKER_'.strtoupper($str));
	}

	//now the time format
	$dtPickerLocalisation['timeFormat'] = 'HH:mm:ss';

	//now the AM & PM settings
	$bod = date_create("now",new DateTimeZone($config->get('offset')));
	$bod->setTime(0,0,0);
	$eod = date_create("now",new DateTimeZone($config->get('offset')));
	$eod->setTime(23,59,59);

	$dtPickerLocalisation['amNames'] = array(Jhtml::_('date',$bod->format(DATE_ATOM),'A'),Jhtml::_('date',$bod->format(DATE_ATOM),'a'));
	$dtPickerLocalisation['pmNames'] = array(Jhtml::_('date',$bod->format(DATE_ATOM),'A'),Jhtml::_('date',$bod->format(DATE_ATOM),'a'));

	//now the RTL
	$dtPickerLocalisation['isRTL'] = $dtLocalisation['isRTL'];

	//let's set the defaults...
	echo '<script>if (jQuery.timepicker != undefined) jQuery.timepicker.setDefaults('.json_encode($dtPickerLocalisation).');</script>';
	echo '<script>if (jQuery.datepicker != undefined) jQuery.datepicker.setDefaults('.json_encode($dtLocalisation).');</script>';

	//lets push the localisation in the doc in case we want to use it elswhere
	$doc = JFactory::getDocument();
	$doc->addScriptDeclaration('var dtLocalisation = '.json_encode($dtLocalisation).';');
	echo '<script>var dtPickerLocalisation = '.json_encode($dtPickerLocalisation).';</script>';

	/* now let's do some full calendar localisation */

	//process some full calendar additions and push these into the dom as well
	$fullCalendarLocale = array();
	$fullCalendarLocale['weekends'] = true;

	//get the min hour max hour
	$shift_times = Pbbookinghelper::get_shift_times();
	$start_hour = 0;
	$end_hour = 0;

	$i=0;
	foreach ($shift_times as $shift_time) {
		if($i==0)
			$start_hour = $shift_time['start_time']['start_hour'];
		$end_hour = $shift_time['end_time']['end_hour'];
		$i++;
	}

	if ($end_hour < 23) $end_hour++;

	$fullCalendarLocale['minTime'] = $start_hour;
	$fullCalendarLocale['maxTime'] = $end_hour;
	$fullCalendarLocale['allDaySlot'] = false;;
	$fullCalendarLocale['firstDay'] = $pbbConfig->calendar_start_day;
	$fullCalendarLocale['dayNamesShort'] = 	$dtLocalisation['dayNamesShort'];
	$fullCalendarLocale['dayNamesLong'] = $dtLocalisation['dayNames'];
	$fullCalendarLocale['monthNamesShort'] = $dtLocalisation['monthNamesShort'];
	$fullCalendarLocale['monthNames'] = $dtLocalisation['monthNames'];
	$fullCalendarLocale['slotMinutes'] = (int)$pbbConfig->time_increment;
	$fullCalendarLocale['slotEventOverlap']	= false;
	$fullCalendarLocale['header'] = array('left'=>'prev,next today','center'=>'title','right'=>'month,agendaWeek,agendaDay');
	$fullCalendarLocale['theme'] = true;
	$fullCalendarLocale['defaultView'] = 'agendaWeek';
	$fullCalendarLocale['columnFormat'] = JText::_('COM_PBBOOKING_MANAGE_DIARIES_NEW_CAL_COLUMN_FORMAT');
	$fullCalendarLocale['editable'] = true;
	$fullCalendarLocale['buttonText'] = array('today'=>JText::_("COM_PBBOOKING_MANAGE_DIARIES_NEW_CAL_TODAY_BUTTON"),
												'month'=>JText::_("COM_PBBOOKING_MANAGE_DIARIES_NEW_CAL_MONTH_BUTTON_"),
												'week'=>JText::_("COM_PBBOOKING_MANAGE_DIARIES_NEW_CAL_WEEK_BUTTON"),
												'day'=>JText::_("COM_PBBOOKING_MANAGE_DIARIES_NEW_CAL_DAY_BUTTON"));

	//just need to hook up the event sources...
	$cals = $db->setQuery('select * from #__pbbooking_cals')->loadObjectList();
	$fullCalendarLocale['eventSources'] = array();
	foreach ($cals as $cal) {
		$fullCalendarLocale['eventSources'][] = array(
				'url'=> JURI::root(false).'administrator/index.php?option=com_pbbooking&controller=manage&task=get_calendar_events&calid='.$cal->id.'&format=raw',
				'color'=> (isset($cal->color)) ? $cal->color : "#339933",
				'textColor'=>'black'
			);
	}



	//push the fullCalendar config into the dom
	$doc->addScriptDeclaration('var fullCalendarLocalisation = '.json_encode($fullCalendarLocale).';');

?>