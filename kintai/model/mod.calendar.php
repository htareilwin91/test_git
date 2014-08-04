<?php
function insertAYearCalendar($array, $mysqli) {
	for ($d = 0; $d < count($array); $d++) {
		$query = "INSERT INTO calendar(calendar_month, calendar_year, calendar_date, calendar_events, calendar_status,calendar_day, create_date, delete_flag)";
		$query .= "VALUES('" . $array[$d]['month'] . "','" . $array[$d]['year'] . "','" . $array[$d]['date'] . "','" . $array[$d]['events'] . "'," . $array[$d]['status'] . ",'" . $array[$d]['day'] . "',NOW(),0)";

		$stmt = $mysqli -> prepare($query) or $mysqli -> error;

		if ($stmt -> execute()) {
			continue;
		}
		return false;
	}
	return true;
}