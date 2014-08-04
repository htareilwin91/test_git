<?php
function checkInCheck($userid, $array, $mysqli) {
	$select = "SELECT * FROM attendance ";
	$select .= "WHERE attd_date = '" . $array["attd_date"] . "' AND attd_user_id = $userid";

	// prevent from check in twice
	if ($stmt = $mysqli -> query($select)) {
		if ($stmt -> num_rows > 0) {
			return true;
		}
	}
	return false;
}

/** get time of checkin **/
function getCheckInTime($userid, $todaydate, $mysqli) {
	$todaydate = $todaydate[0] . "-" . $todaydate[1] . "-" . $todaydate[2];

	$query = "SELECT count(*) FROM attendance ";
	$query .= "WHERE attd_date = '" . $todaydate . "' AND attd_user_id = $userid LIMIT 0,1";

	$stmt = $mysqli -> prepare($query);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($counts);
	$stmt -> fetch();

	if ($counts == 0 && $counts < 1) {
		return false;
	}

	$query = "SELECT * FROM attendance ";
	$query .= "WHERE attd_date = '" . $todaydate . "' AND attd_user_id = $userid LIMIT 0,1";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

/** get time of checkout **/
function getCheckOutTime($userid, $todaydate, $mysqli) {
	$todaydate = $todaydate[0] . "-" . $todaydate[1] . "-" . $todaydate[2];
	
	$query = "SELECT count(*) FROM attendance ";
	$query .= "WHERE attd_date='" . $todaydate . "' AND attd_user_id = $userid AND show_flag = 1 LIMIT 0,1";

	$stmt = $mysqli -> prepare($query);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($counts);
	$stmt -> fetch();

	if ($counts == 0 && $counts < 1) {
		return false;
	}

	$query = "SELECT * FROM attendance ";
	$query .= "WHERE attd_date='" . $todaydate . "' AND attd_user_id = $userid AND show_flag = 1 LIMIT 0,1";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function checkIn($userid, $array, $mysqli) {
	$query = "INSERT INTO attendance(attd_in_time, attd_out_time, attd_user_id, attd_date, attd_comment, create_date, show_flag, delete_flag)";
	$query .= " VALUES('" . $array['attd_in_time'] . "', '" . $array['attd_in_time'] . "', '" . $userid . "', '" . $array['attd_date'] . "', '" . $array['attd_comment'] . "', NOW(), 0, 0)";

	$stmt = $mysqli -> prepare($query);
	if ($stmt -> execute()) {
		return true;
	}
	return false;
}

function checkOutCheckIn($userid, $array, $mysqli) {

	$query = "SELECT * FROM attendance ";
	$query .= "WHERE attd_date='" . $array['attd_date'] . "' AND attd_user_id=$userid";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			return true;
		}
	}
	return false;
}

function checkOutCheckedIn($userid, $array, $mysqli) {
	$query = "SELECT * FROM attendance ";
	$query .= "WHERE attd_date='" . $array['attd_date'] . "' AND attd_user_id=$userid AND show_flag = 1";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			return true;
		}
	}
	return false;
}

function checkOut($userid, $array, $mysqli) {
	$query = "UPDATE attendance SET attd_out_time='" . $array['attd_out_time'] . "', attd_comment='" . $array['attd_comment'] . "', show_flag=1";
	$query .= " WHERE attd_user_id = $userid AND attd_date='" . $array["attd_date"] . "' AND show_flag != 1";

	if ($stmt = $mysqli -> query($query)) {
		return true;
	}
	return false;
}

function getLateCheckIn($userid, $currentmonthyear, $mysqli) {
	$query = "SELECT count(*) as counts FROM attendance a ";
	$query .= "LEFT JOIN calendar c ON a.attd_date = c.calendar_date ";
	$query .= "WHERE attd_in_time > '09:30:00' AND attd_user_id = $userid ";
	$query .= "AND c.calendar_year = '$currentmonthyear[0]' AND c.calendar_month = '$currentmonthyear[1]'";

	$stmt = $mysqli -> prepare($query);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($counts);
	$stmt -> fetch();

	return $counts;
}

function getEarlyCheckOut($userid, $currentmonthyear, $mysqli) {
	$query = "SELECT count(*) FROM attendance a ";
	$query .= "LEFT JOIN calendar c ON a.attd_date = c.calendar_date ";
	$query .= "WHERE attd_out_time < '18:30:00' AND attd_user_id = $userid ";
	$query .= "AND c.calendar_year = '$currentmonthyear[0]' AND c.calendar_month = '$currentmonthyear[1]'";

	$stmt = $mysqli -> prepare($query);
	$stmt -> execute();
	$stmt -> store_result();
	$stmt -> bind_result($counts);
	$stmt -> fetch();

	return $counts;
}

function getAbsent($userid, $currentmonthyear, $mysqli) {

	$query = "SELECT data.attd_date, c.calendar_date, c.calendar_id, c.calendar_status, c.calendar_year, c.calendar_month, calendar_events, calendar_day, data.attd_in_time, data.attd_out_time FROM
					(SELECT a.attd_date, c.calendar_id, c.calendar_status,
					 a.attd_in_time, a.attd_out_time
					 FROM attendance a
					 RIGHT JOIN calendar c ON a.attd_date = c.calendar_date
					 WHERE c.calendar_year = '" . $currentmonthyear[0] . "' AND c.calendar_month = '" . $currentmonthyear[1] . "'
					 AND a.attd_user_id = $userid
					) AS data RIGHT JOIN calendar c ON data.attd_date = c.calendar_date
			WHERE c.calendar_year = '" . $currentmonthyear[0] . "' AND c.calendar_month = '" . $currentmonthyear[1] . "' 
			AND c.calendar_date BETWEEN '" . $currentmonthyear[0] . "-" . $currentmonthyear[1] . "-1' AND '" . $currentmonthyear[0] . "-" . $currentmonthyear[1] . "-" . $currentmonthyear[2] . "' AND c.calendar_status != 0 
			AND attd_in_time IS NULL AND attd_out_time IS NULL";
	//echo $query;
	if ($stmt = $mysqli -> query($query)) {
		$counts = $stmt -> num_rows;

	}
	return $counts;
}

function getCurrentMonth($currentmonthyear, $userid, $mysqli) {	
	$query = "SELECT data.attd_date, c.calendar_date, c.calendar_id, c.calendar_status, c.calendar_year, c.calendar_month, calendar_events, calendar_day, data.attd_in_time, data.attd_out_time FROM
					(SELECT a.attd_date, 
					 c.calendar_id,
					 c.calendar_status,
					 a.attd_in_time,
					 a.attd_out_time
					 FROM attendance a
					 RIGHT JOIN calendar c ON a.attd_date = c.calendar_date
					 WHERE c.calendar_year = '" . $currentmonthyear[0] . "' AND c.calendar_month = '" . $currentmonthyear[1] . "'
					 AND a.attd_user_id = " . $userid . "
					) AS data RIGHT JOIN calendar c ON data.attd_date = c.calendar_date
			WHERE c.calendar_year = '" . $currentmonthyear[0] . "' AND c.calendar_month = '" . $currentmonthyear[1] . "'";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function getmail($mail, $mysqli) {
	$query = "SELECT * FROM user WHERE user_id='" . $mail . "'";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}

function getusername($u_id, $mysqli) {
	$query = "SELECT * FROM user WHERE user_id=$u_id";

	if ($stmt = $mysqli -> query($query)) {
		if ($stmt -> num_rows > 0) {
			while ($result = $stmt -> fetch_assoc()) {
				$data[] = $result;
			}
		} else {
			$data = "";
		}
	}
	return $data;
}
