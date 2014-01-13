<?php
include('bennu_rfc2445.php');
date_default_timezone_set('America/New_York'); 

$startDate 		= $_POST['startDate'];
$endDate 		= $_POST['endDate'];

$icalCreated 	= $_POST['icalCreated'];
$controlType 	= $_POST['controlType']; // INSERT, UPDATE, DELETE
$summary 		= $_POST['summary'];

$now 			= new DateTime();
$year 			= $now->format('Ymd');
$hours 			= $now->format('His');
$timeStamp 		= $year.'T'.$hours.'Z';

class iCalendar_event {

	var $name		= 'VEVENT';
	var $properties = NULL;

	function add_property($name, $value) {
		
		$name 	= strtoupper($name);

		$this->properties[$name][] = $value;

		return true;
	}

	function serialize() {

		if(!empty($this->properties)) {
			foreach($this->properties as $name => $properties) {
				foreach($properties as $property) {
					$string .= rfc2445_fold($name . ":" . $property) . RFC2445_CRLF;
				}
			}
		}
		$string .= rfc2445_fold('END:VCALENDAR'); 

		return $string;
	}
}

$event = new iCalendar_event;

$event->add_property('BEGIN', 'VEVENT');

$event->add_property('CREATED', $timeStamp);
$event->add_property('UID', $timeStamp);
$event->add_property('DTEND;VALUE=DATE', $endDate);
$event->add_property('TRANSP', 'TRANSPARENT');
$event->add_property('SUMMARY', $summary);
$event->add_property('DTSTART;VALUE=DATE', $startDate);
$event->add_property('DTSTAMP', $timeStamp);
$event->add_property('SEQUENCE', '1');
$event->add_property('END', 'VEVENT');

$icsFile = file_get_contents('ical_vacations.ics');

$icsFile_explode = explode("BEGIN:VEVENT", $icsFile);
$firstElement = true;
$updateIcs = "";

if($controlType == "INSERT") {
	$updateIcs .= str_replace('END:VCALENDAR', utf8_encode($event->serialize()), $icsFile);
}

if($controlType == "DELETE" || $controlType == "UPDATE" ) {
	foreach($icsFile_explode as $icsFile_each) {
		$findByCreated = strpos($icsFile_each, 'CREATED:'.$icalCreated);

		
		if ($firstElement) {
			// Skip the first element [BEGIN:VCALENDAR]
			$firstElement = false;
			$updateIcs .= $icsFile_each;
		}
		else {
			// Find By Created
			if($findByCreated > 0) {
				// Modify Data
				if($controlType == "UPDATE") {
					$icsFile_each_explode = explode("\n", $icsFile_each);

					$updateIcs .= 'BEGIN:VEVENT' . RFC2445_CRLF;

					foreach($icsFile_each_explode as $ics_each_line) {

						$findByDtstart 	= strpos($ics_each_line, 'START');
						$findByDtend 	= strpos($ics_each_line, 'END');
						$findByVcalendar= strpos($ics_each_line, 'VCALENDAR');
						$findBySummary	= strpos($ics_each_line, 'UMMARY');

						if($findByDtstart > 0) 			$updateIcs .= 'DTSTART;VALUE=DATE:' . $startDate . RFC2445_CRLF;
						else if($findByDtend > 0) 		$updateIcs .= 'DTEND;VALUE=DATE:' . $endDate . RFC2445_CRLF;
						else if($findBySummary > 0) 		$updateIcs .= 'SUMMARY:' . $summary . RFC2445_CRLF;
						else if($findByVcalendar > 0) 	$updateIcs .= $ics_each_line;
						else if($ics_each_line == "") 	$updateIcs .= '';
						else $updateIcs .= $ics_each_line . RFC2445_CRLF;

					}
				} else if($controlType == "DELETE") {
					// If it is the last child (has [END:VCALENDAR])
					$findByEnd = strpos($icsFile_each, 'END:VCALENDAR');
					if($findByEnd > 0) {
						$updateIcs .= 'END:VCALENDAR';
					}
				}
			} else {
				$updateIcs .= 'BEGIN:VEVENT'.$icsFile_each;
			}
		}
	}
}

echo $timeStamp;

file_put_contents('ical_vacations.ics', ($updateIcs));

   
?>