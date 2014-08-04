<?php
// Insert calendar
if (isset($_POST["formsub"]) && $_POST["formsub"] == "true" && $_POST["formsub"] != "") {
	$day = trim($_POST["day"]);
	$year = trim($_POST["year"]);
	$events = trim($_POST["events"]);

	for ($m = 1; $m <= $month; $m++) { #loop month
	
		for ($d = 1; $d <= $dayspermonth[$m - 1]; $d++) {
				
			$event = "nothing special";
			$status = ($day == 1 || $day == 2) ? "0" : "1"; #determind date is 1:saturday or 2:sunday
			
			for($e = 0; $e < count($mm); $e++) {
				$holidays = explode(":",$mm[$e]);
				
				if($holidays[0] == $m.".".$d) {
					$status = 0;
					$event = $holidays[1];
					break;
				}else {
					continue;
				}
			}
			
			$chunks = array("month" => $m, 
							"year" => $year, 
							"date" => ($year . "-" . $m . "-" . $d), 
							"events" => $event, 
							"status" => $status,
							"day" => $daysname[$day]
			);
			
			if($day < count($daysname)) {
				$day++;
			}else {
				$day = 1;
			}
			
			$data[] = $chunks;
		}
	}
	insertAYearCalendar($data, $db);
}