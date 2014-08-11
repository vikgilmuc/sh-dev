<?php
/**
 * @version		ics_view.php
 * @copyright		Purple Beanie
 * @license		GNU General Public License version 2 or later; 
 */

$cal= "BEGIN:VCALENDAR
CALSCALE:GREGORIAN
VERSION:2.0
X-WR-CALNAME:".$this->event->summary."
METHOD:PUBLISH
PRODID:-//PBBooking//PBBooking 2.2//EN
BEGIN:VTIMEZONE
END:VTIMEZONE
BEGIN:VEVENT
UID:".$this->event->id."@".JURI::root(false)."
DTEND;VALUE=DATE-TIME:".date_create($this->event->dtend,new DateTimeZone(PBBOOKING_TIMEZONE))->format('Ymd')."T".date_create($this->event->dtend,new DateTimeZone(PBBOOKING_TIMEZONE))->format('His')."
DTSTART;VALUE=DATE-TIME:".date_create($this->event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format('Ymd')."T".date_create($this->event->dtstart,new DateTimeZone(PBBOOKING_TIMEZONE))->format('His')."
SUMMARY:".$this->event->summary."
SEQUENCE:0
END:VEVENT
END:VCALENDAR";

header('HTTP/1.1 200 OK');
header('Accept-Ranges: bytes');
header('Content-Length: '.strlen($cal));
header('Content-Type: text/calendar');
header("Content-Disposition: attachment; filename=\"event.ics\""); 
?>
<?php echo $cal;?>