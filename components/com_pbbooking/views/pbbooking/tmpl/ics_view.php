<?php
/**
 * @version		ics_view.php
 * @copyright		Purple Beanie
 * @license		GNU General Public License version 2 or later; 
 */

$cal = "BEGIN:VCALENDAR
CALSCALE:GREGORIAN
VERSION:2.0
X-WR-CALNAME:".$this->cal->name."
BEGIN:VTIMEZONE
END:VTIMEZONE";

foreach ($this->events as $event) {
	$cal.="
BEGIN:VEVENT
DTEND;VALUE=DATE-TIME:".date_create($event->dtend,new DateTimeZone(PBBOOKING_TIMEZONE))->format('Ymd')."T".date_create($event->dtend,new DateTimeZone(PBBOOKING_TIMEZONE))->format('His')."
DTSTART;VALUE=DATE-TIME:".date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format('Ymd')."T".date_create($event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format('His')."
SUMMARY:".$event->summary."
DESCRIPTION:".str_replace("\n","\\n",$event->description)."
END:VEVENT";
}

$cal.="
END:VCALENDAR";

header('HTTP/1.1 200 OK');
header('Accept-Ranges: bytes');
header('Content-Length: '.strlen($cal));
header('Content-Type: text/calendar');

?>
<?php echo $cal;?>